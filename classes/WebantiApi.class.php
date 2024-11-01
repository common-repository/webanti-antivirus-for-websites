<?php
/**
 * API to connect website with Webanti.
 *
 * @package   Webanti
 * @author    Dariusz Tomasiak <msmw23@gmail.com>
 * @copyright Copyright (c) 2017 Webanti
 */

class WebantiApi
{
    /**
     * Version client API.
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Webanti Api URL.
     *
     * @var string
     */
    protected $apiURL = 'https://api.webanti.com/';

    /**
     * Partnership name.
     *
     * @var string
     */
    private $source;

    /**
     * Api key.
     *
     * @var string
     */
    private $apiKey;

    /**
     * Website base URL from client api connect.
     *
     * @var string
     */
    private $websiteURL;

    /**
     * Customer lang (ISO).
     *
     * @var string
     */
    private $lang;

    /**
     * Counstructor
     *
     * @param string $source
     * @param string $apiKey
     * @param string $websiteURL
     */
    public function __construct($source, $apiKey, $websiteURL, $lang)
    {
        $this->lang = $lang;
        $this->source = $source;
        $this->apiKey = $apiKey;
        $this->websiteURL = $websiteURL;
    }


    /**
     * Check url is register in app
     * @param  string $url
     * @return object
     */
    public function getWebsiteStatus()
    {
        return $this->request('api/website/status',
            array('url' => $this->websiteURL),
            'GET'
        );
    }


    /**
     * Check url is register in app
     * @param  string $key
     * @return object
     */
    public function getWebsiteInfo($key = null)
    {
        $key = empty($key) ? $this->apiKey : $key;
        
        return $this->request('api/website/informations',
            array(
                'url' => $this->websiteURL,
                'key' => $key,
            ),
            'GET'
        );
    }


    /**
     * Return plugin scanner code for create
     * @param  string $key
     * @return object
     */
    public function getPlugin($key = null)
    {
        $key = empty($key) ? $this->apiKey : $key;

        return $this->request('api/website/plugin',
            array(
                'url' => $this->websiteURL,
                'key' => $key,
                'phpversion' => phpversion()
            ),
            'GET'
        );
    }


    /**
     * Return plugin scanner code for create
     * @param  string $key
     * @return object
     */
    public function getDynamicContent($key = null)
    {
        $key = empty($key) ? $this->apiKey : $key;

        return $this->request('api/dynamic-content',
            array(
                'url' => $this->websiteURL,
                'key' => $key,
                'lang' => $this->lang
            ),
            'GET'
        );
    }


    /**
     * Create new customer if not exists or add website to register customer
     * @param  string $email
     * @return object
     */
    public function register($email)
    {
        return $this->request('api/users/create',
            array(
                'url' => $this->websiteURL,
                'source' => $this->source,
                'email' => $email,
                'phpversion' => phpversion(),
                'lang' => $this->lang
            )
        );
    }


    /**
     * Method for execute request
     *
     * @param string $path request path
     * @param array $data
     * @param string $method
     * @return object
     */
    private function request($path, $data = array(), $method = 'POST')
    {
        $getParamString = null;

        if ($method === 'GET') {
            $getParamString = '?' . http_build_query( $data );
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiURL . $path . $getParamString);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return (object)array(
            'httpCode' => $httpCode,
            'response' => json_decode($response)
        );
    }

}