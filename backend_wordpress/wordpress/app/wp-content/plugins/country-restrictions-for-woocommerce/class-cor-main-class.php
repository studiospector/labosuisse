<?php
/**
 * Plugin Name:       Country Restrictions for WooCommerce
 * Plugin URI:        https://woocommerce.com/products/country-restrictions-for-woocommerce
 * Description:       Hide products, prices, or add to cart button based on user countries.
 * Version:           1.2.0
 * Author:            Addify
 * Developed By:      Addify
 * Author URI:        http://www.addify.co
 * Support:           http://www.addify.co
 * Domain Path:       /languages
 * Text Domain:       Woo-cor
 * Woo: 7631640:1153ec1d13c657cbb6f2651d3ab579ef
 * WC requires at least: 3.0.9
 * WC tested up to: 6.*.*
 *
 * @package Woo-cor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	/**
	 * Admin Start
	 */
	function cor_admin_notice() {

		$cor_allowed_tags = array(
			'a'      => array(
				'class' => array(),
				'href'  => array(),
				'rel'   => array(),
				'title' => array(),
			),
			'b'      => array(),

			'div'    => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'p'      => array(
				'class' => array(),
			),
			'strong' => array(),
		);

		// Deactivate the plugin.
		deactivate_plugins( __FILE__ );

		$cor_plugin_check = '<div id="message" class="error">
            <p><strong>Country Restrictions for WooCommerce plugin is inactive.</strong> The <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce plugin</a> must be active for this plugin to work. Please install &amp; activate WooCommerce »</p></div>';
		echo wp_kses( esc_attr( $cor_plugin_check ), $cor_allowed_tags );

	}

	add_action( 'admin_notices', 'cor_admin_notice' );
}

if ( ! class_exists( 'Cor_Main_Class' ) ) {

	/**
	 * Class Start
	 */
	class Cor_Main_Class {
		/**
		 * Constructor Start
		 */
		public function __construct() {

			// Define Global Constants.
			$this->cor_global_constents_vars();

			add_action( 'wp_loaded', array( $this, 'cor_init' ) );

			// registration hook setting.
			register_activation_hook( __FILE__, array( $this, 'cor_install_settings' ) );
			// Include other Files.
			if ( is_admin() ) {
				// include Admin Class.
				include_once COR_PLUGIN_DIR . 'admin/class-cor-admin.php';

			} else {
				// include front class.
				include_once COR_PLUGIN_DIR . 'front/class-cor-front.php';
			}
		}

		/**
		 * Cor_global
		 */
		public function cor_global_constents_vars() {

			if ( ! defined( 'COR_URL' ) ) {
				define( 'COR_URL', plugin_dir_url( __FILE__ ) );
			}

			if ( ! defined( 'COR_BASENAME' ) ) {
				define( 'COR_BASENAME', plugin_basename( __FILE__ ) );
			}

			if ( ! defined( 'COR_PLUGIN_DIR' ) ) {
				define( 'COR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}
		}

		/**
		 * Plugin Setting.
		 */
		public function cor_install_settings() {

		}
		
		public function cor_init() {
			if ( function_exists( 'load_plugin_textdomain' ) ) {
				load_plugin_textdomain( ' Woo-cor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
			}
		}







	} // end Class cor_Main_Class.
	new Cor_Main_Class();
}


