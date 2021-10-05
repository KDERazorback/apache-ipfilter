<?php

use \RazorSoftware\IpFilter\IPEntry;
use \RazorSoftware\IpFilter\Analytic;

$stime = time();

require_once __DIR__ . '/ipfilter/config.php';

$filter = IPEntry::getMatchingEntry($_SERVER['REMOTE_ADDR']);

if ($filter != null) {
    if (!headers_sent()) {
        header("X-RZ-Floodgate: " . $filter->id, true);
    }
    if (RZIPF_ENABLE_ANALYTIC == true) {
        $analytic = new Analytic($filter);
        $analytic->save();
    }
    \RazorSoftware\IpFilter\checkRuntime($stime);
    die;
} else {
    if (!headers_sent()) {
        header("X-RZ-Floodgate: 0", true);
    }

    \RazorSoftware\IpFilter\checkRuntime($stime);

    if (defined('RZIPF_PHP_NEXTFILE') && !empty(RZIPF_PHP_NEXTFILE)) {
        require RZIPF_PHP_NEXTFILE;
    }
}
