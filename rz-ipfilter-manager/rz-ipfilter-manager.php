<?php
/**
 * Plugin Name: RazorSoftware IpFilter Manager
 * Plugin URI: https://razorsoftware.dev/
 * Description: Admin interface that allows configuration, data visualization and management of the IPFilter IP address filtering tool by RazorSoftware
 * Version: 0.0.3
 * Requires at least: 4.6
 * Requires PHP: 7.0
 * Author: RazorSoftware
 * Author URI: https://razorsoftware.dev
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once ( __DIR__ . "/includes/config.php" );

require_once ( __DIR__ . "/includes/activation.php" );

$rz_ipfilter_instance = NULL;

require_once RZ_IPFILTER_DIR . '/admin/ipfilter-admin.php';
$options = new RZ_IpFilter_Options();
$options->load();
$rz_ipfilter_instance = new RZ_IpFilter(RZ_IPFILTER_DIR, __RZ_IPFILTER_VERSION__, $options);

register_activation_hook( __FILE__ , 'rz_ipfilter_installer_activate');
register_deactivation_hook( __FILE__, 'rz_ipfilter_installer_deactivate');

?>