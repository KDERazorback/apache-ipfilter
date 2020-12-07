<?php

ini_set('display_errors', '0');
error_reporting(P_ERROR);

if (!defined('ABSPATH'))
    require_once ( __DIR__ . "/../includes/wp_bootstrap.php" );
if (!defined('RZ_IPFILTER_VERSION'))
    require_once ( __DIR__ . "/../includes/config.php" );

if (!function_exists('wp_die') || !function_exists('rz_ipfilter_can_manage'))
    die ('Internal Server Error.');
if (!defined('RZ_IPFILTER_URL') || !defined('RZ_IPFILTER_DIR'))
    die ('Internal Server Error.');

if ($_SERVER['REQUEST_METHOD'] !== "GET")
    die("Access Denied. 0x01");

$country = htmlspecialchars(trim($_GET['cc']));

if ($country == null || empty($country) || !preg_match("/^[a-z]{1,4}/i", $country))
    die("Access Denied. 0x02");

if (strlen($country) > 2)
    $country = substr($country, 0, 2);

$country = strtolower($country);
$cc_top = strtoupper($country);

$file = __DIR__ . "/../external/flags/$country.png";

function send_file($file, $cc) {
    if (!headers_sent()) {
        header("Content-Description: Flag Image for $cc");
        header('Content-Type: image/png');
        header('Content-Length: ' . filesize($file));
    }
    flush();
    readfile($file);
    flush();
}

if (file_exists($file)) {
    send_file($file, $cc_top);
} else {
    $file = __DIR__ . "/../external/no_flag.png";
    send_file($file, $cc_top);
}

die;