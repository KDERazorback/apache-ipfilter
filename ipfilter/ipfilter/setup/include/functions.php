<?php 
use Ifsnop\Mysqldump\Mysqldump;

/* CONSTANTS */
define('RZIP_SESSION_LASTACTIVITY', 'rzipf_setup_last_activity');
define('RZIP_SESSION_SETUPAUTH'   , 'rzipf_setup_auth');
define('RZIP_SESSION_LOGFILE'     , 'rzipf_setup_logfile');
define('RZIP_SESSION_STARTTIME'   , 'rzipf_setup_start_time');
define('RZIP_SESSION_STATUS'      , 'rzipf_setup_status');


/* FUNCTIONS */
function writeLog($line, $overwrite = FALSE) {
    echo '<p>' . $line . '</p>';

    
    if (isset($_SESSION[RZIP_SESSION_LOGFILE]) && !empty($_SESSION[RZIP_SESSION_LOGFILE])) {
        if (file_exists($_SESSION[RZIP_SESSION_LOGFILE]) && $overwrite !== TRUE)
            file_put_contents($_SESSION[RZIP_SESSION_LOGFILE], $line . "\n", FILE_APPEND);
        else
            file_put_contents($_SESSION[RZIP_SESSION_LOGFILE], $line . "\n");
    }
}

function getLogfile() {
    if (isset($_SESSION[RZIP_SESSION_LOGFILE]) && !empty($_SESSION[RZIP_SESSION_LOGFILE])) {
        return $_SESSION[RZIP_SESSION_LOGFILE];
    }
    
    return (__DIR__ . '/../logs/setuplog-' . time() . '.log');
}

function execSql($conn, $sql) {
    if ($conn->multi_query($sql) === FALSE) {
        return NULL;
    }

    while ($conn->more_results())
        $conn->next_result();

    return true;
}

function execSqlLines($conn, $filename, $update_callback) {
    $f = fopen($filename, 'r');
    $index = 0;
    while (!feof($f)) {
        $index++;
        $line = trim(fgets($f));
        
        if (!$update_callback($index, $line))
            continue;

        $conn->query($line);
        $conn->store_result();
        
        if ($conn->errno > 0) {
            $err = $conn->errno;
            return $err;
        }
    }

    fclose($f);

    return true;
}


function requireFileWrite($filename, $data, $mode = 0) {
    if (!file_put_contents($filename, $data, $mode))
    {
        writeLog("IO Error. Cannot write file to disk.");
        http_response_code(500);
        writeLog("INTERNAL ERROR");
        die;
    }
}

function closeConnection($conn) {
    if (!empty($conn)) {
        $conn->close();
    }    
}

function dumpSql($host, $user, $pass, $schema, $filename) {
    if (empty($host) || empty($user) || empty($schema) || empty($filename))
        throw new Exception("Invalid arguments");

    $mysqldump = new Mysqldump('mysql:host=' . $host . ';dbname=' . $schema, $user, $pass);
    $mysqldump->start($filename);
}

function tableExists($conn, $table) {
    if (empty($conn))
        throw new Exception("Invalid connection");

    if (preg_match('/^[0-9A-Za-z\-_]+$/', $table) !== 1)
        throw new Exception("Invalid table name");

    $conn->query('SELECT 1 FROM `' . $table . '` LIMIT 1');
    $conn->store_result();

    return ($conn->errno === 0);
}

function is_cli() {
    return (strcasecmp(php_sapi_name(), 'cli'));
}

function session_started()
{
    if (!is_cli()) {
        if (function_exists('session_status')) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else if (function_exists('session_id')) {
            return session_id() === '' ? FALSE : TRUE;
        } else {
            return FALSE;
        }
    }
    return FALSE;
}

function handleException($e) {
    if (session_started()) {
        $_SESSION[RZIP_SESSION_STATUS] = -1;
    }
}

/* Debug mode section START */
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// if (function_exists('xdebug_break')) xdebug_break();
/* Debug mode section END */
?>