<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
require_once __DIR__ . '/includes/options.class.php';

delete_option('rz_ipfilter_options');
delete_role('rz_ipfilter_manager');
get_role('administrator')->remove_cap('rz_ipfilter_manage', true);

$opt = new RZ_IpFilter_Options();
$opt->delete();
?>