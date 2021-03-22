<?php
/**
 * Plugin Name: Woo Ajax Filter
 * Plugin URI: https://github.com/shakh9006/woo-filter/
 * Description: WooCommerce Filter Add-on.
 * Author: Shakh Nematov
 * Text Domain: woo-filter
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WF_VERSION', '1.0.0' );
define( 'WF_DB_VERSION', '1.0.0');
define( 'WF_PATH', dirname( __FILE__ ) );
define( 'WF_BASE_URL', '/1/api' );
define( 'WF_URL', plugins_url( '', __FILE__ ) );
define( 'WF_PLUGIN_FILE', __FILE__ );

if (function_exists('icl_object_id')){
    global $sitepress;
    define('WF_DEFAULT_LANG', $sitepress->get_default_language());
}else {
    define('WF_DEFAULT_LANG', '');
}

if ( ! is_textdomain_loaded( 'woo-filter' ) ) {
    load_plugin_textdomain(
        'woo-filter',
        false,
        'woo-filter/language'
    );
}

require_once __DIR__ . '/includes/autoload.php';