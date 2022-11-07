<?php
/**
 * Plugin Name: Google Tag Manager for WooCommerce PRO
 * Plugin URI: https://tagconcierge.com/google-tag-manager-for-woocommerce/
 * Description: Push WooCommerce eCommerce (Enhanced Ecommerce and GA4) information to GTM DataLayer. Use any GTM integration to measure your customers' activites.
 * Version:     1.7.2
 * Author:      Handcraft Byte
 * Author URI:  https://handcraftbyte.com/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gtm-ecommerce-woo
 * Domain Path: /languages
 *
 * WC requires at least: 4.0
 * WC tested up to: 6.8.1
 * Woo: 7424130:288e08109d3061ad3a8886a94db87d9b
 */

namespace GtmEcommerceWooPro;

require __DIR__ . '/vendor/autoload.php';

use GtmEcommerceWooPro\Lib\Container;

$pluginData = get_file_data(__FILE__, array('Version' => 'Version'), false);
$pluginVersion = $pluginData['Version'];


$container = new Container($pluginVersion);

$container->getSettingsService()->initialize();
$container->getGtmSnippetService()->initialize();
$container->getEventStrategiesService()->initialize();
$pluginService = $container->getPluginService();
$pluginService->initialize();
$container->getThemeValidatorService()->initialize();
$container->getEventInspectorService()->initialize();

$monitorService = $container->getMonitorService();
$monitorService->initialize();

register_activation_hook( __FILE__, array( $pluginService, 'activationHook' ) );
register_deactivation_hook( __FILE__, [$monitorService, 'deactivationHook'] );
