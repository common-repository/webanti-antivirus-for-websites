<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
Plugin Name: Webanti
Plugin URI:  https://webanti.com/
Description: Simple integration with webanti.
Version:     1.1
Author:      Webanti
Author URI:  https://webanti.com/
License:     LGPL3
License URI: http://opensource.org/licenses/LGPL-3.0
Text Domain: webanti
Domain Path: /languages
*/
define( 'WEBANTI_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );


function plugin_init() {
    update_option( 'WEBANTI_CUSTOMER_APIKEY', '', false );
    update_option( 'WEBANTI_CUSTOMER_SCANNER', '', false );
    update_option( 'WEBANTI_CUSTOMER_SCANNER_UPDATE_TIME', '', false );
}

function plugin_deactive() {
    delete_option( 'WEBANTI_CUSTOMER_APIKEY' );
    delete_option( 'WEBANTI_CUSTOMER_SCANNER' );
    delete_option( 'WEBANTI_CUSTOMER_SCANNER_UPDATE_TIME' );
}

function webanti_helper_class() {
    
    require_once WEBANTI_PLUGIN_PATH . 'classes/Webanti.class.php';
    
    return new Webanti(
        site_url(),
        admin_url('admin.php?page=webanti'),
        substr(get_bloginfo ( 'language' ), 0, 2),
        get_home_path(), 
        get_option('WEBANTI_CUSTOMER_APIKEY'),
        get_option('WEBANTI_CUSTOMER_SCANNER'),
        get_option('WEBANTI_CUSTOMER_SCANNER_UPDATE_TIME')
    );
}

register_activation_hook( __FILE__, 'plugin_init' );
register_deactivation_hook( __FILE__, 'plugin_deactive' );


if ( is_admin() ) {
    require_once( WEBANTI_PLUGIN_PATH . '/admin/webanti-admin.php' );
}


function webanti_load_textdomain() {
    load_plugin_textdomain( 'webanti', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('plugins_loaded', 'webanti_load_textdomain');
