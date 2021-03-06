<?php
    $stime = time();

    require_once __DIR__ . '/ipfilter/config.php';

    $filter = \RazorSoftware\IpFilter\IPEntry::getMatchingEntry($_SERVER['REMOTE_ADDR']);

    if ($filter != NULL) {
        if (!headers_sent())
            header("X-RZ-Floodgate: " . $filter->id, TRUE);
        if (RZIPF_ENABLE_ANALYTIC == TRUE) {
            $analytic = new \RazorSoftware\IpFilter\Analytic($filter);
            $analytic->save();
        }
        \RazorSoftware\IpFilter\checkRuntime($stime);
        die;
    } else {
        if (!headers_sent())
            header("X-RZ-Floodgate: 0", TRUE);
        
        \RazorSoftware\IpFilter\checkRuntime($stime);

        if(defined('RZIPF_PHP_NEXTFILE') && !empty(RZIPF_PHP_NEXTFILE))
            require RZIPF_PHP_NEXTFILE;
    }
?>