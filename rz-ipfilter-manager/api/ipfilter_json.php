<?php
if (!defined('ABSPATH'))
    require_once ( __DIR__ . "/../includes/wp_bootstrap.php" );
if (!defined('__RZ_IPFILTER_VERSION__'))
    require_once ( __DIR__ . "/../includes/config.php" );

if (!function_exists('wp_die') || !function_exists('rz_ipfilter_can_manage'))
    die ('Internal Server Error.');
if (!defined('RZ_IPFILTER_URL') || !defined('RZ_IPFILTER_DIR'))
    die ('Internal Server Error.');

if ($_SERVER['REQUEST_METHOD'] !== "POST")
    die("Access Denied. 0x01");

$action = htmlspecialchars(trim($_POST['action']));

if ($action == null || empty($action))
    die("Access Denied. 0x02");

require_once ( __DIR__ . '/../includes/RzDbConnection.class.php' );

$result = array();
$result['Action']=$action;
$result['Records']=array();

$return_json = function ($result, $code = 'OK', $msg = '') {
    $result['Result'] = $code;
    $result['Message'] = empty($msg) ? "$code " . $result['Action'] : $msg;
    $result['RecordCount'] = (!is_null($result['Records']) && is_array($result['Records'])) ? sizeof($result['Records']) : 0;
    echo json_encode($result);
    exit;
};

require_once ( __DIR__ . '/../includes/AnalyticRecords.class.php');

$offset = isset($_POST['startIndex']) ? $_POST['startIndex'] : 0;
if (!is_numeric($offset) || $offset < 0)
    die("Request rejected. Malformed data.");
$count = isset($_POST['pageSize']) ? $_POST['pageSize'] : $_POST['maxCount'];
if (empty($count))
    $count = 20;
if (!is_numeric($count) || $count < 1)
    die("Request rejected. Malformed data.");
$groupby = $_POST['groupby'];
if (!empty($groupby) && !is_string($groupby))
    die("Request rejected. Malformed data.");

switch ($action) {
    case 'getHitRecords':
        $opt = new RZ_IpFilter_Options();
        $opt->load();
        if (!$opt->valid())
            die("Request rejected. Server not configured.");
        $conn = new Rz_IpFilter_DbConnection($opt->Hostname, $opt->Username, $opt->Password, $opt->Schema);
        $instance = new Rz_IpFilter_AnalyticRecords($conn, $opt);

        $records = $instance->getRecords($offset, $count, $groupby);
        $result['Records'] = $records;

        $record_count = $instance->getRecordCount();

        $result['TotalRecordCount'] = $record_count;
        $result['startIndex'] = $offset;

        $return_json($result, 'OK');
        break;

    default:
        $return_json($result, 'ERROR', "The server did not understand your request. Invalid action");
        break;
}

?>