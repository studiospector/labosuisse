<?php
/**
 * Plugin Name: Country Based Restrictions PRO
 * Plugin URI: https://www.zorem.com/products/country-based-restriction-pro
 * Description: The Country Based Restrictions pro extends the Country Based Restrictions for WooCommerce plugin and allows you to using more options and products restriction by categories.
 * Version: 3.6
 * Author: zorem
 * Author URI: https://zorem.com/
 * Text Domain: country-base-restrictions-pro-addon
 * Domain Path: /lang/
 *
 * Woo:
 * WC requires at least: 4.0
 * WC tested up to: 6.8
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package zorem
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Country_Based_Restrictions_PRO {

	/**
	 * Country Based Restrictions PRO
	 *
	 * @since 1.0
	 * @var string
	 */
	public $version = '3.6';	
	
	/**
	 * Initialize the main plugin function
	 *
	 * @since 1.0
	*/
    public function __construct() {
		
		// check if WC is active
		if ( $this->is_wc_active() ) {		

			require_once $this->get_plugin_path() . '/includes/cbr-pro-license.php';
			$this->license = CBR_PRO_License::get_instance();
			
			//update-manager
			require_once $this->get_plugin_path() . '/includes/cbr-pro-update-manager.php';
			new CBR_Pro_Update_Manager (
				$this->version,
				'country-base-restrictions-pro-addon/country-base-restrictions-pro-addon.php',
				$this->license->get_item_code()
			);

			$subscription_status = $this->license->check_subscription_status();
			
			$this->includes_for_all();

			if ( $subscription_status ) {
				$this->includes();
				$this->init();
			}
		} else {
			add_action( 'admin_notices', array( $this, 'admin_error_notice' ) );	
		}
	}
	
	/**
	 * WOOCOMMERCE_VERSION admin notice
	 *
	 * @since 1.0
	 */
	function admin_error_notice() {
		$message = esc_html( 'Country Based Restrictions requires WooCommerce 3.0 or newer', 'country-base-restrictions-pro-addon' );
		echo "<div class='error'><p>$message</p></div>";
	}
	
	/**
	 * Check if WC is active
	 *
	 * @access private
	 * @since  1.0.0
	 * @return bool
	*/
	private function is_wc_active() {
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$is_active = true;
		} else {
			$is_active = false;
		}

		// Do the WC active check
		if ( false === $is_active ) {
			add_action( 'admin_notices', array( $this, 'notice_activate_wc' ) );
		}		
		return $is_active;
	}
	
	/**
	 * Display WC active notice
	 *
	 * @access public
	 * @since  1.0
	*/
	public function notice_activate_wc() {
		?>
		<div class="error">
			<p><?php printf( esc_html( 'Please install and activate %sWooCommerce%s for Country Based Restrictions PRO!', 'country-base-restrictions-pro-addon' ), '<a href="' . admin_url( 'plugin-install.php?tab=search&s=WooCommerce&plugin-search-input=Search+Plugins' ) . '">', '</a>' ); ?></p>
		</div>
		<?php
	}

	public function includes_for_all() {
		
		require_once $this->get_plugin_path() . '/includes/cbr-pro-admin.php';
		$this->admin = CBR_PRO_Admin::get_instance();
		
		require_once $this->get_plugin_path() . '/includes/cbr-pro-notice.php';
		$this->notice = CBR_PRO_Notice::get_instance();

	}
	
	/**
	 * Include plugin file.
	 *
	 * @since 1.0
	 */	
	function includes() {
		
		require_once $this->get_plugin_path() . '/includes/cbr-pro-product.php';
		$this->product = CBR_PRO_Product::get_instance();
		
		require_once $this->get_plugin_path() . '/includes/cbr-pro-restriction.php';
		$this->restriction = CBR_PRO_Restriction::get_instance();
		
		require_once $this->get_plugin_path() . '/includes/cbr-pro-widget.php';
		$this->widget = CBR_PRO_Widget::get_instance();
		
		require_once $this->get_plugin_path() . '/includes/cbr-pro-toolbar.php';
		$this->toolbar = CBR_PRO_Toolbar::get_instance();
		
	}
	
	/*
	* init when class loaded
	*
	* @since 1.0
	*/
	public function init() {			

		//adding hooks

		//hooks in admin plugin page	
		add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ),  array( $this , 'my_plugin_action_links' ) );
		
		// Load plugin textdomain
		add_action('plugins_loaded', array($this, 'load_pro_textdomain' ) );
		
		register_activation_hook( __FILE__, array( $this,'on_activation' ) );
		
	}
	
	/*
	* call on plugin activation
	* 
	* @since 2.4
	*/
	function on_activation() {
		deactivate_plugins( 'woo-product-country-base-restrictions/woocommerce-product-country-base-restrictions.php' );
		set_transient( "free_cbr_plugin", "notice", 3 );
		update_option( 'cbr_pro_plugin_notice_ignore', 'true' );
	}
	
	/**
	 * Add plugin action links.
	 *
	 * Add a link to the settings page on the plugins.php page.
	 *
	 * @since 1.0
	 *
	 * @param  array  $links List of existing plugin action links.
	 * @return array         List of modified plugin action links.
	 */
	function my_plugin_action_links( $links ) {
		$links = array_merge( 
			array( '<a href="' . esc_url( admin_url( '/admin.php?page=woocommerce-product-country-base-restrictions' ) ) . '">' . esc_html( 'Settings', 'woocommerce' ) . '</a>' ),
			array( '<a href="' . esc_url( 'https://www.zorem.com/docs/country-based-restrictions-for-woocommerce/' ) . '" target="_blank">' . esc_html( 'Docs', 'woocommerce' ) . '</a>' ),
			array( '<a href="' . esc_url( 'https://www.zorem.com/my-account/contact-support/' ) . '" target="_blank">' . esc_html( 'Support', 'woocommerce' ) . '</a>' ),
			array( '<a href="' . esc_url( 'https://wordpress.org/support/plugin/woo-product-country-base-restrictions/reviews/#new-post' ) . '" target="_blank">' . esc_html( 'Review', 'woocommerce' ) . '</a>' ), 
			$links );
		return $links;
	}
	
	/*
	 * plugin file directory function
	 *
	 * @since 1.0
	*/	
	public function plugin_dir_url() {
		return plugin_dir_url( __FILE__ ); 
	}
	
	/*
	 * load text-domain
	 *
	 * @since 1.0
	*/
	public function load_pro_textdomain() {
		
		load_plugin_textdomain( 'country-base-restrictions-pro-addon', false, plugin_dir_path( plugin_basename(__FILE__) ) . 'lang/' );

		require_once $this->get_plugin_path() . '/includes/customizer/cbr-customizer.php';				
		require_once $this->get_plugin_path() . '/includes/customizer/cbr-widget-customizer.php';
	
	}
	
	/**
	 * Gets the absolute plugin path without a trailing slash, e.g.
	 * /path/to/wp-content/plugins/plugin-directory.
	 *
	 * @since 1.0
	 * @return string plugin path
	 */
	public function get_plugin_path() {
		if ( isset( $this->plugin_path ) ) {
			return $this->plugin_path;
		}

		$this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );

		return $this->plugin_path;
	}
	
}

/**
 * Returns an instance of Country_Based_Restrictions_PRO.
 *
 * @since 1.0
 * @version 1.0
 * @return Country_Based_Restrictions_PRO
*/
function cbr_pro_addon() {
	static $instance;

	if ( ! isset( $instance ) ) {		
		$instance = new Country_Based_Restrictions_PRO();
	}

	return $instance;
}

/**
 * Register this class globally.
 *
 * Backward compatibility.
*/
cbr_pro_addon();
