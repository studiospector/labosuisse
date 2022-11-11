<?php

/**
 * Plugin Name: Caffeina Country Based Restrictions
 * Plugin URI: https://caffeina.com
 * Description: Caffeina Country Based Restrictions for WooCommerce plugin that allows you to using restrictions on products.
 * Version: 1.0.0
 * Author: Caffeina
 * Author URI: https://caffeina.com/
 * Text Domain: caffeina-country-based-restrictions
 * Domain Path: /lang/
 *
 * Woo:
 * WC requires at least: 4.0
 * WC tested up to: 7.1.0
 *
 * @package caffeina-country-based-restrictions
 */

if (!defined('ABSPATH')) {
	exit;
}

class Caffeina_Country_Based_Restrictions
{
	/**
	 * Caffeina Country Based Restrictions
	 *
	 * @since 1.0
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * Initialize the main plugin function
	 *
	 * @since 1.0
	 */
	public function __construct()
	{
		// Check if WC is active
		if ($this->is_wc_active()) {
			$this->includes();
			$this->init();
		} else {
			add_action('admin_notices', array($this, 'admin_error_notice'));
		}
	}

	/**
	 * WOOCOMMERCE_VERSION admin notice
	 *
	 * @since 1.0
	 */
	function admin_error_notice()
	{
		$message = esc_html__('Caffeina Country Based Restrictions richiede WooCommerce 3.0 o successivo', 'caffeina-country-based-restrictions');
		echo "<div class='error'><p>$message</p></div>";
	}

	/**
	 * Check if WC is active
	 *
	 * @access private
	 * @since  1.0.0
	 * @return bool
	 */
	private function is_wc_active()
	{
		if (!function_exists('is_plugin_active')) {
			require_once(ABSPATH . '/wp-admin/includes/plugin.php');
		}
		if (is_plugin_active('woocommerce/woocommerce.php')) {
			$is_active = true;
		} else {
			$is_active = false;
		}

		// Do the WC active check
		if (false === $is_active) {
			add_action('admin_notices', array($this, 'notice_activate_wc'));
		}
		return $is_active;
	}

	/**
	 * Display WC active notice
	 *
	 * @access public
	 * @since  1.0
	 */
	public function notice_activate_wc()
	{
		?>
		<div class="error">
			<p>
				<?php
				printf(
					esc_html__(
						'Installa e attiva %sWooCommerce%s per Caffeina Country Based Restrictions!',
						'caffeina-country-based-restrictions'
					),
					'<a href="' . admin_url('plugin-install.php?tab=search&s=WooCommerce&plugin-search-input=Search+Plugins') . '">',
					'</a>'
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Include plugin file
	 *
	 * @since 1.0
	 */
	function includes()
	{
		require_once $this->get_plugin_path() . '/includes/ccbr-restrictions.php';
		$this->restriction = CCBR_Restrictions::get_instance();
	}

	/**
	 * Init when class loaded
	 *
	 * @since 1.0
	 */
	public function init()
	{
		//hooks in admin plugin page	
		// add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ),  array( $this , 'my_plugin_action_links' ) );

		// Load plugin textdomain
		add_action('plugins_loaded', array($this, 'load_textdomain'));

		// register_activation_hook( __FILE__, array( $this,'on_activation' ) );
	}

	/**
	 * Call on plugin activation
	 * 
	 * @since 2.4
	 */
	// function on_activation() {}

	/**
	 * Add plugin action links
	 *
	 * Add a link to the settings page on the plugins.php page.
	 *
	 * @since 1.0
	 */
	// function my_plugin_action_links( $links ) {
	// 	$links = array_merge(
	// 		array( '<a href="' . esc_url('http://test.test') . '">' . esc_html( 'Settings', 'woocommerce' ) . '</a>' ),
	// 		$links
	// 	);
	// 	return $links;
	// }

	/**
	 * Plugin file directory function
	 *
	 * @since 1.0
	 */
	public function plugin_dir_url()
	{
		return plugin_dir_url(__FILE__);
	}

	/**
	 * Load text-domain
	 *
	 * @since 1.0
	 */
	public function load_textdomain()
	{
		load_plugin_textdomain('caffeina-country-based-restrictions', false, plugin_dir_path(plugin_basename(__FILE__)) . 'lang/');
	}

	/**
	 * Gets the absolute plugin path without a trailing slash, e.g.
	 * /path/to/wp-content/plugins/plugin-directory.
	 *
	 * @since 1.0
	 * @return string plugin path
	 */
	public function get_plugin_path()
	{
		if (isset($this->plugin_path)) {
			return $this->plugin_path;
		}

		$this->plugin_path = untrailingslashit(plugin_dir_path(__FILE__));

		return $this->plugin_path;
	}
}



/**
 * Returns an instance of Caffeina_Country_Based_Restrictions.
 *
 * @since 1.0
 * @version 1.0
 * @return Caffeina_Country_Based_Restrictions
 */
function ccbr_init()
{
	static $instance;
	
	if (!isset($instance)) {
		$instance = new Caffeina_Country_Based_Restrictions();
	}

	return $instance;
}

/**
 * Register this class globally
 * Backward compatibility
 */
ccbr_init();
