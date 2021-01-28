<?php
namespace RazorSoftware\IpFilter;

define('__RZIPF_DRIVER_VERSION__', '1.0.0');

function shouldLog() {
    return (defined('RZIPF_LOG_EVENTS') && RZIPF_LOG_EVENTS === TRUE);
}

function log($line) {
    if (defined('RZIPF_LOG_EVENTS') && RZIPF_LOG_EVENTS === TRUE) {
        if (!file_exists(RZIPF_LOG_FILENAME))
            file_put_contents(RZIPF_LOG_FILENAME, '=== LOG INITIALIZED ON ' . date(DATE_ATOM, time()) . "\n");
        
    file_put_contents(RZIPF_LOG_FILENAME, 
        sprintf("[%s]\t[driver:%s]\t%s\n",
            date(DATE_ATOM, time()),
            (defined('__RZIPF_DRIVER_VERSION__') ? __RZIPF_DRIVER_VERSION__ : 'unset'),
            $line
    ));
    }
}

function checkRuntime($stime) {
    if (!is_numeric($stime) || $stime < 0)
        return;

    $t = time() - $stime;

    if ($t > 2 && shouldLog()) {
        log("WARNING: slow driver performance [%s sec]", $t);
    }
}
?>

