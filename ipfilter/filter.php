<?php
    require_once __DIR__ . '/ipfilter/config.php';

    $filter = IPEntry::getMatchingEntry($_SERVER['REMOTE_ADDR']);

    if ($filter != NULL) {
        if (!headers_sent())
            header("X-RZ-Floodgate: " . $filter->id, TRUE);
        if (RZIPF_ENABLE_ANALYTIC == TRUE) {
            $analytic = new Analytic($filter);
            $analytic->save();
        }
        die;
    } else {
        if (!headers_sent())
            header("X-RZ-Floodgate: 0", TRUE);
        
        require RZIPF_PHP_NEXTFILE;
    }
?>