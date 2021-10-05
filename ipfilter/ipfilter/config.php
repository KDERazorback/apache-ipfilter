<?php
/* 
 * Options for managing connection to the filter database
 * RZIPF_DB_HOST
 * RZIPF_DB_USER
 * RZIPF_DB_PASSWORD
 * RZIPF_DB_SCHEMA
 * RZIPF_DB_TABLE_PREFIX
 *      Contains settings used for connecting to the MySQL DBMS.
 */
define("RZIPF_DB_HOST", "localhost");
define("RZIPF_DB_USER", "master");
define("RZIPF_DB_PASSWORD", "1234");
define("RZIPF_DB_SCHEMA", "ipfilter");
define("RZIPF_DB_TABLE_PREFIX", 'live_');

/* 
 * RZIPF_DB_TABLE_IPENTRY
 * RZIPF_DB_TABLE_ANALYTICS
 *      Contains tables names for every object stored on the MySQL DBMS.
 */
define('RZIPF_DB_TABLE_IPENTRY', RZIPF_DB_TABLE_PREFIX . "ipentry");
define('RZIPF_DB_TABLE_ANALYTICS', RZIPF_DB_TABLE_PREFIX . "analytic");
define('RZIPF_DB_TABLE_IPENTRYCACHE', RZIPF_DB_TABLE_PREFIX . "ipentrycache");

/*
 * RZIPF_ENABLE_ANALYTIC
 *      Enable writting analytic data to the database
 */
define('RZIPF_ENABLE_ANALYTIC', true);

/*
 * RZIPF_PHP_NEXTFILE
 *      Specifies the next file that will be required on the PHP chain
 */
define('RZIPF_PHP_NEXTFILE', '');

/*
 * RZIPF_ALLOW_SETUP
 *      Enables the usage of the setup.php script
 */
define('RZIPF_ALLOW_SETUP', true);


/*
 * RZIPF_LOG_EVENTS
 *      Enables logging of incoming driver calls to disk
 */
define('RZIPF_LOG_EVENTS', true);
define('RZIPF_LOG_FILENAME', __DIR__ . '/logs/driver.log');


/*
 * PHP Class loading (manual)
 */
require_once __DIR__ . '/include/DbConnection.class.php';
require_once __DIR__ . '/include/Analytic.class.php';
require_once __DIR__ . '/include/IPEntry.class.php';
require_once __DIR__ . '/include/IPEntryCache.class.php';
require_once __DIR__ . '/include/functions.php';
