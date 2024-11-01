<?php

class Webanti
{
    private $api;
    private $website_url;
    private $module_url;
    private $lang_iso;
    private $root_dir;
    private $apiKey;
    private $scanner;

    public function __construct($website_url, $module_url, $lang_iso = 'en', $root_dir, $apiKey, $scanner)
    {
        $this->website_url = $website_url;
        $this->module_url = $module_url;
        $this->lang_iso = $lang_iso;
        $this->root_dir = $root_dir;
        $this->apiKey = $apiKey;
        $this->scanner = $scanner;

        require_once dirname(__FILE__) . '/WebantiApi.class.php';

        $this->api = new WebantiApi(
            $source = 'wordpress',
            $this->apiKey,
            $this->website_url,
            $this->lang_iso
        );

    }


    public function getConfiguration($key, $value)
    {
        return get_option($key, $value);
    }


    public function updateConfiguration($key, $value)
    {
        return update_option($key, $value);
    }


    public function createQuarantineCatalog()
    {
        if (file_exists($this->root_dir.'/quarantine/') && is_writable($this->root_dir.'/quarantine/')) {
            file_put_contents($this->root_dir.'/quarantine/index.html', '');
        } elseif (mkdir($this->root_dir.'/quarantine/', 0777, true)) {
            file_put_contents($this->root_dir.'/quarantine/index.html', '');
        } else {
            return $this->displayError('Catalog "quarantine" not exists or is not writable.');
        }

        return true;
    }


    public function getWebsiteInfo($apiKey)
    {
        $request = $this->api->getWebsiteInfo($apiKey);
        $dynamic = $this->api->getDynamicContent($apiKey);

        if ($dynamic->httpCode == 200) {
            $result  = array(
                'dynamicContent' => $dynamic->response->data->html
            );
        } else {
            $result  = array(
                'dynamicContent' => ''
            );
        }


        if ($request->httpCode == 404) {
            return array(
                'webantiScannerStatus' => 0,
                'webantiWebsiteStatus' => 99,
                'webantiPlanName' => 0,
                'webantiPlanExpireDate' => 0,
            );
        }
        
        if (file_exists($this->root_dir . '/' . $this->scanner) && isset($request->response->data->plan_name)) {
            $result['webantiScannerStatus'] = 1;
        } else {
            $result['webantiScannerStatus'] = 0;
        }

        $result['webantiWebsiteStatus'] = $request->response->data->status;
        $result['webantiPlanName'] = '';
        $result['webantiPlanExpireDate'] = '';

        if (isset($request->response->data->plan_name)) {
            $result['webantiPlanName'] = $request->response->data->plan_name;
        }

        if (isset($request->response->data->plan_expire)) {
            $result['webantiPlanExpireDate'] = $request->response->data->plan_expire;
        }

        return $result;
    }


    public function displayError($message)
    {   
        return '<div class="error notice"><p>' .  __($message, 'webanti') . '</p></div>';
    }


    public function displayConfirmation($message)
    {
        return '<div class="updated notice"><p>' . __($message, 'webanti') . '</p></div>';
    }


    public function checkScannerStatus()
    {
        $request = $this->api->getPlugin();

        if ($request->httpCode == 401) {
            return $this->displayError('Invalid API KEY.');
        }   

        if ( file_exists( get_home_path() . '/' .  $this->scanner ) ) {
            if ($request->response->website_status == 3) {
                return $this->displayError('Scanner is installed, but site has been stopped in Webanti app.');
            } else {
                return $this->displayConfirmation('Scanner is installed!');
            }
        }

        return $this->displayError('Scanner is not installed.');
    }


    private function connectPlugin($apiKey, $redirect = 1)
    {   
        $catalog = $this->createQuarantineCatalog();
        $request = $this->api->getPlugin($apiKey);

        if ($catalog !== true) {
            return $catalog;
        }

        switch ($request->httpCode) {

            case 200:
                $this->updateConfiguration('WEBANTI_CUSTOMER_APIKEY', $apiKey);
                $this->updateConfiguration('WEBANTI_CUSTOMER_SCANNER', $request->response->name);

                if (file_put_contents($this->root_dir . '/' . $request->response->name, base64_decode($request->response->scanner))) {
                    if ($redirect == 1) {
                        return 'ok';
                    }
                }

                return $this->displayError('Failed automatically install scanner.');

            case 401:
                return $this->displayError('Invalid API KEY.');
            
            default:
                return $this->displayError('Error. Code #' . $request->httpCode);
        }

    }


    public function formConnect($key = null)
    {
        $key = empty($key) ? $this->apiKey : $key;

        return $this->connectPlugin( $key );
    }


    public function formRegister() 
    {
        $email = filter_var($_POST['WEBANTI_CUSTOMER_EMAIL'], FILTER_SANITIZE_EMAIL);
        $terms = isset($_POST['terms']) ? $_POST['terms'] : 0;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->displayError('Invalid e-mail address.');
        }

        if (empty($terms)) {
            return $this->displayError('Accept the Terms and the Privacy Policy of Webanti is required.');
        }

        $request = $this->api->register($email);

        switch ($request->httpCode) {
            case 200:
                if ($request->response->website->apiKey) {
                    $this->updateConfiguration( 'WEBANTI_CUSTOMER_APIKEY', $request->response->website->apiKey );
                }

                if ($request->response->user->status == 1) {
                    return $this->connectPlugin($request->response->website->apiKey);
                }

            case 409:
                if ($request->response->code == 1) {
                    return $this->displayError('Max domains. Upgrade your plan in Webanti.');
                }

                if ($request->response->code == 2) {
                    return $this->displayError('Website exists.');
                }

                return $this->displayError('We cannot create user and add website.');
            
            default:
                return $this->displayError('Error. Code #' . $request->httpCode);
        }

    }


    public function getContent()
    {   
        $msg = '';
        $html = '';

        if ($_POST && isset($_POST['btnConnect'])) {
            $this->formConnect($_POST['WEBANTI_CUSTOMER_APIKEY']);
        }

        if ($_POST && isset($_POST['btnRegister'])) {
            $msg .= $this->formRegister();
        }

        $request = $this->api->getWebsiteStatus();

        switch ($request->httpCode) {
            case 200:

                if ($request->httpCode == 200 && $request->response->data->user_status == 0) {
                    $msg .= $this->displayError('You must verify your account. Link to verify has been sent to your e-mail address.');
                } else {
                    if ($_POST && isset($_POST['btnConnect'])) {
                        $msg = $this->checkScannerStatus();
                    }
                }

                $html .= $this->renderForm();
                break;

            case 404:
                $this->updateConfiguration('WEBANTI_CUSTOMER_APIKEY', '');
                $this->updateConfiguration('WEBANTI_CUSTOMER_SCANNER', '');
                $html .= $this->renderRegisterForm();
                break;

            default:
                if ($request->httpCode != 0) {
                    $msg .= $this->displayError('Error. Code #' . $request->httpCode);
                }
                $html .= $this->renderRegisterForm();
                break;
        }

        return (object)array(
            'message' => $msg, 
            'html' => $html, 
            'websiteInfo' => $this->getWebsiteInfo( $this->apiKey )
        );
    }


    public function renderForm()
    {
        return 'form_connect';
    }


    public function renderRegisterForm()
    {
        return 'form_register';
    }

}