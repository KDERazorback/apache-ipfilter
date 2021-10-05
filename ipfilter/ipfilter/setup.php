<?php

use \RazorSoftware\IpFilter\Setup\GeoIpDatabase;


if (!file_exists(__DIR__ . '/setup') || file_exists(__DIR__ . '/setup/installed')) {
    http_response_code(403);
    echo "ALREADY INSTALLED";
    die;
}

require_once __DIR__ . '/config.php';

if (!defined("RZIPF_ALLOW_SETUP") || RZIPF_ALLOW_SETUP !== TRUE) {
    http_response_code(403);
    echo "SETUP IS DISABLED";
    die;
}

require_once __DIR__ . '/setup/include/functions.php';
require_once __DIR__ . '/setup/config_setup.php';

/* SESSION */
if (!session_start()) die;

$_SESSION[RZIP_SESSION_LASTACTIVITY] = time();
$_SESSION[RZIP_SESSION_STARTTIME] = time();
$_SESSION[RZIP_SESSION_SETUPAUTH] = 1;
$_SESSION[RZIP_SESSION_LOGFILE] = getLogfile();
$_SESSION[RZIP_SESSION_STATUS] = 0;

session_write_close();

set_exception_handler('handleException');


/* MAIN */
writeLog("Starting IPFilter setup...", TRUE);

/* Load Mustache */
Mustache_Autoloader::register();

$m = new Mustache_Engine(array('entity_flags' => ENT_QUOTES));

writeLog("\tReading templates...");
$sql_template = file_get_contents(__DIR__ . '/setup/templates/ipfilter.template.sql');
$sql_data_template_insert = file_get_contents(__DIR__ . '/setup/templates/ipfilter_data_insert.template.sql');
$sql_data_template_head =   file_get_contents(__DIR__ . '/setup/templates/ipfilter_data_head.template.sql');
$sql_data_template_footer = file_get_contents(__DIR__ . '/setup/templates/ipfilter_data_footer.template.sql');
$sql_data_template_insert = str_replace(array("\r", "\n", '\t'), ' ', $sql_data_template_insert) . "\r\n";
if (session_start()) {
    $_SESSION[RZIP_SESSION_STATUS] = 1;
    session_write_close();
}

$config = array(
    'SCHEMA'            => RZIPF_DB_SCHEMA,
    'TABLE_PREFIX'      => RZIPF_DB_TABLE_PREFIX,
    'TABLE_ENTRY'       => RZIPF_DB_TABLE_IPENTRY,
    'TABLE_ENTRYCACHE'  => RZIPF_DB_TABLE_IPENTRYCACHE,
    'TABLE_ANALYTIC'    => RZIPF_DB_TABLE_ANALYTICS,
    'DATE'          => date(DATE_ATOM, time())
);
$config_geo = array(
    'SCHEMA'        => RZIPF_GEO_DB_SCHEMA,
    'TABLE'         => RZIPF_GEO_DB_TABLE,
    'REGIONS'       => RZIPF_GEO_FILTERED_REGIONS,
    'DATE'          => date(DATE_ATOM, time())
);


/* Save customized SQL structure file */
writeLog("Writting schema to disk...");
$sql_schema = $m->render($sql_template, $config);
requireFileWrite(__DIR__ . '/setup/ipfilter.sql', $sql_schema);
if (session_start()) {
    $_SESSION[RZIP_SESSION_STATUS] = 2;
    session_write_close();
}


/* Save SQL data entries to disk */
writeLog("Extracting filter list from GeoDB...");
$sql_data_filename = __DIR__ . '/setup/ipfilter_data.sql';
requireFileWrite($sql_data_filename, $m->render($sql_data_template_head, $config_geo));
if (session_start()) {
    $_SESSION[RZIP_SESSION_STATUS] = 3;
    session_write_close();
}

$geo_data = new GeoIpDatabase(
    RZIPF_GEO_DB_HOST,
    RZIPF_GEO_DB_USER,
    RZIPF_GEO_DB_PASSWORD,
    RZIPF_GEO_DB_SCHEMA,
    RZIPF_GEO_DB_TABLE
);

$geo_data->select(RZIPF_GEO_FILTERED_REGIONS)
    ->execute()
    ->iterate(function ($i, $data) use ($sql_data_filename, $m, $sql_data_template_insert) {
        if ($i % 500 === 0) writeLog("\t\t..." . $i . " records updated...");
        $data["TABLE"]   = RZIPF_DB_TABLE_IPENTRY;
        $data["SCHEMA"]  = RZIPF_DB_SCHEMA;
        $data["REGIONS"] = RZIPF_GEO_FILTERED_REGIONS;
        return file_put_contents($sql_data_filename, $m->render($sql_data_template_insert, $data), FILE_APPEND) > 0;
    })
    ->close();
writeLog($geo_data->getExportCount() . " Records written.");
if (session_start()) {
    $_SESSION[RZIP_SESSION_STATUS] = 4;
    session_write_close();
}

requireFileWrite(
    $sql_data_filename,
    $m->render(
        $sql_data_template_footer,
        array_merge(
            $config_geo,
            array(
                'ROWS' => $geo_data->getExportCount()
            )
        )
    ),
    FILE_APPEND
);
if (session_start()) {
    $_SESSION[RZIP_SESSION_STATUS] = 5;
    session_write_close();
}

/* General-purpose mysqli connection to iptables schema */
$conn_ipfilter = new mysqli(RZIPF_DB_HOST, RZIPF_DB_USER, RZIPF_DB_PASSWORD, RZIPF_DB_SCHEMA);
$unixtime = time();
if (session_start()) {
    $_SESSION[RZIP_SESSION_STATUS] = 6;
    session_write_close();
}

$backup_tables = array(
    RZIPF_DB_TABLE_IPENTRY,
    RZIPF_DB_TABLE_IPENTRYCACHE,
    RZIPF_DB_TABLE_ANALYTICS
);

writeLog("Checking live db...");
foreach ($backup_tables as $table) {
    if (tableExists($conn_ipfilter, $table)) {
        writeLog("\t...Backing up " . $table);
        dumpSql(RZIPF_DB_HOST, RZIPF_DB_USER, RZIPF_DB_PASSWORD, RZIPF_DB_SCHEMA, __DIR__ . '/setup/archived/archived_' . $table . '-' . $unixtime . '.sql');
    }
}
if (session_start()) {
    $_SESSION[RZIP_SESSION_STATUS] = 7;
    session_write_close();
}


/* Create online DB structure */
writeLog("Updating online database...");
$result = execSql($conn_ipfilter, $sql_schema);
if ($result !== TRUE) {
    closeConnection($conn_ipfilter);
    writeLog("Failed to update online database.");
    if (session_start()) {
        $_SESSION[RZIP_SESSION_STATUS] = -1;
        session_write_close();
    }
    http_response_code(500);
    header('X-Error-Code: ' . $result, true);
    writeLog("INTERNAL ERROR");
    die();
}
if (session_start()) {
    $_SESSION[RZIP_SESSION_STATUS] = 8;
    session_write_close();
}


/* Populate online DB database */
writeLog("Inserting " . $geo_data->getExportCount() . " records...");
$result = execSqlLines($conn_ipfilter, $sql_data_filename, function ($i, $line) {
    if ($i % 1000 === 0) writeLog("\t\t..." . $i . " records added...");

    return (stripos($line, "insert") === 0 ||
        stripos($line, "set") === 0 ||
        stripos($line, "use") === 0);
});

if ($result !== TRUE) {
    closeConnection($conn_ipfilter);
    writeLog("Failed to update online database.");
    if (session_start()) {
        $_SESSION[RZIP_SESSION_STATUS] = -1;
        session_write_close();
    }
    http_response_code(500);
    header('X-Error-Code: ' . $result, true);
    writeLog("INTERNAL ERROR");
    die();
}
writeLog($geo_data->getExportCount() . " records inserted.");
if (session_start()) {
    $_SESSION[RZIP_SESSION_STATUS] = 9;
    session_write_close();
}

closeConnection($conn_ipfilter);

http_response_code(200);
writeLog("Driver installation succeeded");

file_put_contents(__DIR__ . '/setup/installed', date(DATE_ATOM, time()));

die();

/* EOS */
