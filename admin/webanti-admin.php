<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function webanti_admin_assets() {
    wp_enqueue_style( 'webanti-style', plugins_url( 'css/webanti.css', __FILE__ ) );
}

function webanti_menu() {
	add_menu_page( 'Webanti - Security', 'Webanti', 'manage_options', 'webanti', 'webanti_admin_page', 'dashicons-shield-alt' );
}

function webanti_admin_page() {

	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

    $webantiClass = webanti_helper_class();
    $content = $webantiClass->getContent();

    if ($content->message === 'ok' || substr(strip_tags($content->message), 0, 2) === 'ok' || ($_POST && isset($_POST['btnConnect']))) {
        print('<script>window.location.href="' . admin_url('admin.php?page=webanti') . '"</script>');
        exit();
    }

    echo $content->message;

    if ( $content->html == 'form_connect' ) {
        require_once WEBANTI_PLUGIN_PATH . 'admin/views/form.connect.php';
    } else {
        require_once WEBANTI_PLUGIN_PATH . 'admin/views/form.register.php';
    }

	require_once WEBANTI_PLUGIN_PATH . 'admin/views/configure.php';

}

add_action( 'admin_menu', 'webanti_menu' );
add_action( 'admin_enqueue_scripts', 'webanti_admin_assets' );