<?php
if (!defined('ABSPATH')) {
    die;
}
if (!defined('__RZ_IPFILTER_VERSION__')) { 
    die;
}

define('RZ_IPFILTER_OPTION_ENDPOINT', 'rz_ipfilter_endpoint');

define('RZ_IPFILTER_OPTION_HOSTNAME', 'rz_ipfilter_db_hostname');
define('RZ_IPFILTER_OPTION_SCHEMA', 'rz_ipfilter_db_schema');
define('RZ_IPFILTER_OPTION_USERNAME', 'rz_ipfilter_db_username');
define('RZ_IPFILTER_OPTION_PASSWORD', 'rz_ipfilter_db_password');
define('RZ_IPFILTER_OPTION_PREFIX', 'rz_ipfilter_db_prefix');

define('RZ_IPFILTER_OPTION_GEOIP_SCHEMA', 'rz_ipfilter_db_geoipschema');
define('RZ_IPFILTER_OPTION_GEOIP_TABLENAME', 'rz_ipfilter_db_geoiptablename');
define('RZ_IPFILTER_OPTION_GEOIP_PREFIX', 'rz_ipfilter_db_geoipprefix');
define('RZ_IPFILTER_OPTION_CONFIGURED', 'rz_ipfilter_configured');


class RZ_IpFilter_Options {
    public $Endpoint = '';

    public $Hostname = 'localhost';
    public $Schema = 'ipfilter';
    public $Username = '';
    public $Password = '';
    public $TablePrefix = 'live_';

    public $GeoipSchema = 'iptables';
    public $GeoipTableName = 'geoip';
    public $GeoipTablePrefix = 'live_';

    public $configured = FALSE;

    public function valid() {
        return !(empty($this->Username) ||
            empty($this->Hostname) ||
            empty($this->Schema) ||
            !$this->Configured);
    }

    public function create() {
        add_option(RZ_IPFILTER_OPTION_ENDPOINT, $this->Endpoint);

        add_option(RZ_IPFILTER_OPTION_HOSTNAME, $this->Hostname);
        add_option(RZ_IPFILTER_OPTION_SCHEMA, $this->Schema);
        add_option(RZ_IPFILTER_OPTION_USERNAME, $this->Username);
        add_option(RZ_IPFILTER_OPTION_PASSWORD, $this->Password);
        add_option(RZ_IPFILTER_OPTION_PREFIX, $this->TablePrefix);

        add_option(RZ_IPFILTER_OPTION_GEOIP_SCHEMA, $this->GeoipSchema);
        add_option(RZ_IPFILTER_OPTION_GEOIP_TABLENAME, $this->GeoipTableName);
        add_option(RZ_IPFILTER_OPTION_GEOIP_PREFIX, $this->GeoipTablePrefix);

        add_option(RZ_IPFILTER_OPTION_CONFIGURED, $this->Configured);
    }

    public function delete() {
        delete_option(RZ_IPFILTER_OPTION_ENDPOINT);

        delete_option(RZ_IPFILTER_OPTION_HOSTNAME);
        delete_option(RZ_IPFILTER_OPTION_SCHEMA);
        delete_option(RZ_IPFILTER_OPTION_USERNAME);
        delete_option(RZ_IPFILTER_OPTION_PASSWORD);
        delete_option(RZ_IPFILTER_OPTION_PREFIX);

        delete_option(RZ_IPFILTER_OPTION_GEOIP_SCHEMA);
        delete_option(RZ_IPFILTER_OPTION_GEOIP_TABLENAME);
        delete_option(RZ_IPFILTER_OPTION_GEOIP_PREFIX);

        delete_option(RZ_IPFILTER_OPTION_CONFIGURED);
    }

    public function save() {
        update_option(RZ_IPFILTER_OPTION_ENDPOINT, $this->Endpoint);

        update_option(RZ_IPFILTER_OPTION_HOSTNAME, $this->Hostname);
        update_option(RZ_IPFILTER_OPTION_SCHEMA, $this->Schema);
        update_option(RZ_IPFILTER_OPTION_USERNAME, $this->Username);
        update_option(RZ_IPFILTER_OPTION_PASSWORD, $this->Password);
        update_option(RZ_IPFILTER_OPTION_PREFIX, $this->TablePrefix);

        update_option(RZ_IPFILTER_OPTION_GEOIP_SCHEMA, $this->GeoipSchema);
        update_option(RZ_IPFILTER_OPTION_GEOIP_TABLENAME, $this->GeoipTableName);
        update_option(RZ_IPFILTER_OPTION_GEOIP_PREFIX, $this->GeoipTablePrefix);

        update_option(RZ_IPFILTER_OPTION_CONFIGURED, TRUE);
    }

    public function load() {
        $this->Endpoint = get_option(RZ_IPFILTER_OPTION_ENDPOINT, '');

        $this->Hostname = get_option(RZ_IPFILTER_OPTION_HOSTNAME, 'localhost');
        $this->Schema = get_option(RZ_IPFILTER_OPTION_SCHEMA, 'ipfilter');
        $this->Username = get_option(RZ_IPFILTER_OPTION_USERNAME, '');
        $this->Password = get_option(RZ_IPFILTER_OPTION_PASSWORD, '');
        $this->TablePrefix = get_option(RZ_IPFILTER_OPTION_PREFIX, 'live_');

        $this->GeoipSchema = get_option(RZ_IPFILTER_OPTION_GEOIP_SCHEMA, 'geoip');
        $this->GeoipTableName = get_option(RZ_IPFILTER_OPTION_GEOIP_TABLENAME, 'iptable');
        $this->GeoipTablePrefix = get_option(RZ_IPFILTER_OPTION_GEOIP_PREFIX, 'live_');

        $this->Configured = get_option(RZ_IPFILTER_OPTION_CONFIGURED, FALSE);
    }

    public function getArray() {
        $output;

        $output['endpoint']=$this->Endpoint;

        $output['db_hostname']=$this->Hostname;
        $output['db_schema']=$this->Schema;
        $output['db_username']=$this->Username;
        $output['db_password']=$this->Password;
        $output['db_tableprefix']=$this->TablePrefix;

        $output['geoip_schema']=$this->GeoipSchema;
        $output['geoip_tablename']=$this->GeoipTableName;
        $output['geoip_tableprefix']=$this->GeoipTablePrefix;

        $output['configured']=$this->Configured;

        return $output;
    }
}

?>