<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!defined('__RZ_IPFILTER_VERSION__') || !defined('RZ_IPFILTER_ROLE_MANAGE')) { 
    die;
}

function rz_ipfilter_installer_activate() {
    add_role(RZ_IPFILTER_ROLE_MANAGE, 'RZ IpFilter Manager', array(RZ_IPFILTER_ROLE_MANAGE => true));
    $role=get_role('administrator');
    $role->add_cap(RZ_IPFILTER_CAP_MANAGE, true);

    $options = new RZ_IpFilter_Options();
    $options->create();
}

function rz_ipfilter_installer_deactivate() {
    delete_option('rz_ipfilter_options');
    remove_role('rz_ipfilter_manager');
    get_role('administrator')->remove_cap('rz_ipfilter_manage', true);

    $opt = new RZ_IpFilter_Options();
    $opt->delete();
}
?>