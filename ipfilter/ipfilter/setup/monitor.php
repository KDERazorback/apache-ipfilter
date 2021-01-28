<?php
require_once (__DIR__ . '/include/functions.php');

/* SESSION */
if (!session_start()) {
    http_response_code(403);
    echo "FORBIDDEN";
    die;
};

$installed = is_file(__DIR__ . '/installed');
$activity = isset($_SESSION[RZIP_SESSION_LASTACTIVITY]) ? $_SESSION[RZIP_SESSION_LASTACTIVITY] : 0;
$starttime = isset($_SESSION[RZIP_SESSION_STARTTIME]) ? $_SESSION[RZIP_SESSION_STARTTIME] : 0;
$auth = isset($_SESSION[RZIP_SESSION_SETUPAUTH]) ? $_SESSION[RZIP_SESSION_SETUPAUTH] : 0;
$statuscode = isset($_SESSION[RZIP_SESSION_STATUS]) ? $_SESSION[RZIP_SESSION_STATUS] : 0;


if ($auth !== 1 || !is_numeric($activity) ||
    !is_numeric($starttime) || $starttime < 1) {
    http_response_code(403);
    echo "FORBIDDEN";
    die;
}
    
$dtime = time() - $activity;
$stime = time() - $starttime;
if ($dtime < 0 || $dtime > 300 || $stime > 1800) {
    session_destroy();
    http_response_code(410);
    echo "GONE";
    die;
}
    
$_SESSION[RZIP_SESSION_LASTACTIVITY] = time();

$filename = isset($_SESSION[RZIP_SESSION_LOGFILE]) ? $_SESSION[RZIP_SESSION_LOGFILE] : '';

session_write_close();

if (empty($filename) || strlen($filename) < 4 || !file_exists($filename) || !is_file($filename)) {
    session_destroy();
    http_response_code(500);
    echo "INTERNAL SERVER ERROR";
    die;
}

$f = fopen($filename, 'r');
if (!$f) {
    session_destroy();
    http_response_code(500);
    echo "IO ERROR";
    die;
}

$logdata = array();
$index = 0;
while (!feof($f)) {
    $index++;
    $line = trim(fgets($f));

    array_push($logdata, $line);
}
fclose($f);

$status = '';

if ($installed === TRUE)
    $status = "completed";
else if ($statuscode < 0) {
    $status = "failed";
} else {
    $status = "running";
}

http_response_code(200);
echo json_encode([
    'log' => $logdata,
    'status' => $status,
    'starttime' => $starttime,
]);
die;
?>