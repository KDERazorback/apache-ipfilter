<?php
    /*
     * PHP Class loading (manual)
     */
    require_once (__DIR__ . '/../include/DbConnection.class.php');
    require_once (__DIR__ . '/../include/IPEntry.class.php');
    require_once (__DIR__ . '/include/GeoIpDatabase.class.php');
    require_once (__DIR__ . '/include/Mustache/Autoloader.php');
    require_once (__DIR__ . '/include/Ifsnop/Mysqldump/Mysqldump.php');


    /* 
     * Options for managing connection to the GeoIP Database
     * RZIPF_GEO_DB_HOST
     * RZIPF_GEO_DB_USER
     * RZIPF_GEO_DB_PASSWORD
     * RZIPF_GEO_DB_SCHEMA
     * RZIPF_GEO_DB_TABLE
     *      Contains settings used for connecting to the MySQL DBMS.
     */
    define("RZIPF_GEO_DB_HOST", "localhost");
    define("RZIPF_GEO_DB_USER", "master");
    define("RZIPF_GEO_DB_PASSWORD", "1234");
    define("RZIPF_GEO_DB_SCHEMA", "geoip");
    define("RZIPF_GEO_DB_TABLE", 'live_iptable');

    /*
     * RZIPF_GEO_FILTERED_REGIONS
     *      Contains a list of regions that will be filtered out by the IPFilter driver
     */
    define("RZIPF_GEO_FILTERED_REGIONS", array(
        'CH',
        'AF',
        'IN',
        'RU'
    ));
?>