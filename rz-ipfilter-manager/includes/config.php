<?php

define('RZ_IPFILTER_DIR',  realpath(dirname (__DIR__)));
define('RZ_IPFILTER_ROLE_MANAGE', 'rz_ipfilter_manager');
define('RZ_IPFILTER_CAP_MANAGE', 'rz_ipfilter_manage');
define('RZ_IPFILTER_OPTIONS', 'rz_ipfilter_options');

if (!defined('__RZ_IPFILTER_VERSION__'))
    define('__RZ_IPFILTER_VERSION__', 'unset');

if (function_exists('plugins_url'))
    define('RZ_IPFILTER_URL', dirname (plugins_url( '', __FILE__ )));
else {
    $dir = dirname( realpath(__DIR__) );
    $docroot = $_SERVER['DOCUMENT_ROOT'];
    while (!empty($docroot) && $docroot[strlen($docroot) - 1] == '/')
        $docroot = substr($docroot, strlen($docroot) - 1);
    if (empty($docroot))
        die ("Invalid server DOCUMENT_ROOT configuration.");

    $docroot = realpath($docroot);
    $path=$dir;
    while (!empty($path) && $path != '/' && strlen($path) > 1) {
        if (strcmp($path, $docroot) == 0) {
            $relpath = substr($dir, strlen($docroot));
            if (empty($relpath) || strlen($relpath) < 1)
                $relpath = '/';
            
            $url = $relpath;
            break;
        }
        $path=realpath(dirname($path));
    }

    if (empty($url) || $url == '/')
        die ("Internal Directory Configuration error.");
    
    define('RZ_IPFILTER_URL', $url);
}

function rz_ipfilter_log( $msg )
{
    #echo '<script>console.log("' . print_r( $msg, true ) . '");</script>';

    if ( is_array( $msg ) || is_object( $msg ) ) {
        error_log( print_r( $msg, true ) );
    } else {
        error_log( $msg );
    }
}

require_once ( __DIR__ . "/options.class.php" );

function rz_ipfilter_can_manage() {
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        if (current_user_can(RZ_IPFILTER_CAP_MANAGE))
            return true;
    }

    return false;
}
?>