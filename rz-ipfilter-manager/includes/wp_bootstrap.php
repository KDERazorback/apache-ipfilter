<?php

$getWordpressPath = function () {
    $basePath = $_SERVER['DOCUMENT_ROOT'];

    if (empty($basePath))
        return NULL;

    $pwd = __DIR__;

    while (TRUE) {
        $candidate = $pwd . '/wp-load.php';
        
        if (file_exists($candidate)) {
            return $candidate;
        }

        if (strcmp($pwd, $basePath) === 0) {
            return NULL; // End of document root
        }

        $new_pwd = dirname($pwd);
        if (strcmp($pwd, $new_pwd) === 0) {
            return NULL; // Loop detected
        }

        $pwd = $new_pwd;
    }
};

// Bring up the WP environment
session_start();
define('SHORTINIT', true);
$loader = $getWordpressPath();
$config = __DIR__ . '/config.php';

if (file_exists($loader))
    require_once  $loader;
else
    die("Access Denied. Code 0x01");

if (!file_exists($config))
    die("Access Denied. Code 0x02");

header('Content-Type: text/html');
header('X-RZ-Application: RZ-CoreIPFilter');

// At this point onwards, WP should be available but not initialized
send_nosniff_header(); // Send no-sniff headers
wp_not_installed(); // Check if wordpress is installed, die if not
nocache_headers(); // Send no-cache headers

// Load RZ user-data
require_once ( ABSPATH . WPINC . '/class-wp-user.php' );
require_once ( ABSPATH . WPINC . '/class-wp-roles.php' );
require_once ( ABSPATH . WPINC . '/class-wp-role.php' );
require_once ( ABSPATH . WPINC . '/class-wp-session-tokens.php' );
require_once ( ABSPATH . WPINC . '/class-wp-user-meta-session-tokens.php' );
require_once ( ABSPATH . WPINC . '/formatting.php' );
require_once ( ABSPATH . WPINC . '/capabilities.php' );
require_once ( ABSPATH . WPINC . '/user.php' );
require_once ( ABSPATH . WPINC . '/meta.php' );

wp_cookie_constants();

require_once ( ABSPATH . WPINC . '/vars.php' );
require_once ( ABSPATH . WPINC . '/kses.php' );
require_once ( ABSPATH . WPINC . '/rest-api.php' );
require_once ( ABSPATH . WPINC . '/pluggable.php' );

# Wordpress functions required by config.php
if (!function_exists('is_user_logged_in'))
    die("Access Denied. Code 0x20");

if (!function_exists('wp_get_current_user'))
    die("Access Denied. Code 0x21");

if (!function_exists('current_user_can'))
    die("Access Denied. Code 0x22");

require_once $config;

# Elements defined in config.php
if (!defined('__RZ_IPFILTER_VERSION__'))
    die("Access Denied. Code 0x10");

if (!defined('RZ_IPFILTER_ROLE_MANAGE'))
    die("Access Denied. Code 0x11");

if (!defined('RZ_IPFILTER_CAP_MANAGE'))
    die("Access Denied. Code 0x12");

if (!rz_ipfilter_can_manage())
    die("Access Denied. Code 0x30");

# Elements defined in options.class.php
if (!class_exists('RZ_IpFilter_Options'))
    die("Access Denied. Code 0x23");

if (!function_exists('get_option'))
    die("Access Denied. Code 0x25");

$rz_filter_options = new RZ_IpFilter_Options();
$rz_filter_options->load();

?>