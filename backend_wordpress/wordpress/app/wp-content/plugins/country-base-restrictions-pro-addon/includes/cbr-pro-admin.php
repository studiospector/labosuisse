<?php
/**
 * CBR pro admin 
 *
 * @class   CBR_PRO_Admin
 * @since 1.0
 * @package WooCommerce/Classes
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CBR_PRO_Admin class.
 *
 * @since 1.0
*/
class CBR_PRO_Admin {

	/**
	 * Get the class instance
	 *
	 * @since  1.0
	 * @return CBR_PRO_Admin
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Instance of this class.
	 *
	 * @since  1.0
	 * @var object Class Instance
	*/
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	 * 
	 * @since  1.0
	*/
	public function __construct() {
		$this->init();
	}
	
	/*
	 * init function
	 *
	 * @since  1.0
	*/
	public function init() {
		
		//adding hooks
		
		add_action( 'admin_menu', array( $this, 'register_woocommerce_menu' ), 99 );

		//load javascript in admin
		add_action('admin_enqueue_scripts', array( $this, 'cbr_enqueue_pro' ) );

		//hooks for frontend
		add_action( 'wp_head', array( $this, 'wc_cbr_frontend_enqueue' ), 999 );
		
		//ajax save admin api settings
		add_action( 'wp_ajax_cbr_setting_form_update', array( $this, 'cbr_setting_form_update_callback' ) );
		
		$page = isset( $_GET["page"] ) ? $_GET["page"] : "";
		if( 'woocommerce-product-country-base-restrictions' == $page ){
			// Hook for add admin body class in settings page
			add_filter( 'admin_body_class', array( $this, 'cbr_post_admin_body_class' ), 100 );
		}
		
		//callback for Pro WC Product Meta Export
		add_filter( 'woocommerce_product_export_meta_value',array( $this, 'prepare_cbr_meta_for_export' ), 10, 4 );
		
		//callback for Pro WC Product Meta Import	
		add_filter( 'woocommerce_product_import_pre_insert_product_object',array( $this, 'prepare_cbr_meta_for_import' ), 10, 2 );
				
		//ajax save admin
		add_action( 'wp_ajax_save_cbr_bulk_restrictions', array( $this, 'save_cbr_bulk_restrictions_callback' ) );
		add_action( 'wp_ajax_save_cbr_checkout_restrictions', array( $this, 'save_cbr_checkout_restrictions_callback' ) );
		add_action( 'wp_ajax_cbr_payment_gateway_form_update', array( $this, 'save_cbr_payment_gateway_callback' ) );
		
		//filter for bulk action option in product
		add_filter( 'bulk_actions-edit-product', array( $this, 'add_cbr_remove_rule_bulk_actions' ) );
		
		//filter for bulk action handler
		add_filter( 'handle_bulk_actions-edit-product', array( $this, 'remove_cbr_rule_bulk_action_handler' ), 10, 3 );
		
		//filter for bulk action notices
		add_action( 'admin_notices', array( $this, 'cbr_removed_rule_bulk_action_notices' ) );
				
		//widget preview function
		add_action( 'template_redirect', array( $this, 'run_preview_func') );
						
	}
	
	/*
	 * Admin Menu add function
	 *
	 * @since  2.4
	 * WC sub menu 
	*/
	public function register_woocommerce_menu() {
		add_submenu_page( 'woocommerce', 'Country Restrictions', 'Country Restrictions', 'manage_options', 'woocommerce-product-country-base-restrictions', array( $this, 'woocommerce_product_country_restrictions_page_callback' ) );
	}
	
	/*
	 * add class in body tag for settings page
	 *
	 * @since  2.4
	*/
	function cbr_post_admin_body_class( $body_class ) {
		
		$body_class .= ' woocommerce-country-based-restrictions';
 
    	return $body_class;
	}

	/*
	* Add admin javascript
	*
	* @since 1.0
	*/	
	public function cbr_enqueue_pro() {
		
		$page = isset( $_GET["page"] ) ? $_GET["page"] : "" ;
		
		// Add condition for css & js include for admin page  
		if ( $page != 'woocommerce-product-country-base-restrictions' ) {
			return;
		}
		
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';	
		
		// Add the WP Media 
		wp_enqueue_media();
		
		wp_enqueue_style( 'select2-cbr', cbr_pro_addon()->plugin_dir_url() . 'assets/css/select2.min.css' );
		wp_enqueue_script( 'select2-cbr', cbr_pro_addon()->plugin_dir_url() . 'assets/js/select2.min.js' );
		
		wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
		wp_register_script( 'jquery-blockui', WC()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI' . $suffix . '.js', array( 'jquery' ), '2.70', true );
		wp_enqueue_style( 'woocommerce_admin_styles' );
		wp_enqueue_script( 'jquery-blockui' );
		
		// Add tiptip js and css file		
		wp_enqueue_style( 'cbr-admin-css', cbr_pro_addon()->plugin_dir_url() . 'assets/css/admin.css', array(), cbr_pro_addon()->version );
		wp_enqueue_script( 'cbr-admin-js', cbr_pro_addon()->plugin_dir_url() . 'assets/js/admin.js', array( 'jquery', 'wp-util', 'wp-color-picker','jquery-tiptip' ), cbr_pro_addon()->version, true );
		
	}

	/*
	 * Add frontend css
	 *
	 * @since 1.0
	*/	
	public function wc_cbr_frontend_enqueue() {	

		if ( '1' != get_option('wpcbr_hide_restricted_product_variation') && !is_product() ) {
			return;
		}
		wp_enqueue_style( 'cbr-fronend-css', cbr_pro_addon()->plugin_dir_url() . 'assets/css/frontend.css', array(), cbr_pro_addon()->version );
	}
	
	/*
	 * callback for Sales Report Email page
	 *
	 * @since  2.4
	*/
	public function woocommerce_product_country_restrictions_page_callback() {

		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : '' ;
		?>
		<div class="zorem-layout__header">
			<img class="zorem-layout__header-logo" src="<?php echo esc_url(cbr_pro_addon()->plugin_dir_url() . 'assets/images/cbr-logo.png'); ?>">			
			<div class="woocommerce-layout__activity-panel">
				<div class="woocommerce-layout__activity-panel-tabs">
					<button type="button" id="activity-panel-tab-help" class="components-button woocommerce-layout__activity-panel-tab">
						<span class="dashicons dashicons-editor-help"></span>
						Help 
					</button>
				</div>
				<div class="woocommerce-layout__activity-panel-wrapper">
					<div class="woocommerce-layout__activity-panel-content" id="activity-panel-true">
						<div class="woocommerce-layout__activity-panel-header">
							<div class="woocommerce-layout__inbox-title">
								<p class="css-activity-panel-Text">Documentation</p>            
							</div>								
						</div>
						<div>
							<ul class="woocommerce-list woocommerce-quick-links__list">
								<li class="woocommerce-list__item has-action">
									<?php
									$support_link = class_exists( 'Country_Based_Restrictions_PRO' ) ? 'https://www.zorem.com/?support=1' : 'https://wordpress.org/support/plugin/woo-product-country-base-restrictions/#new-topic-0' ;
									?>
									<a href="<?php echo esc_url( $support_link ); ?>" class="woocommerce-list__item-inner" target="_blank" >
										<div class="woocommerce-list__item-before">
											<img src="<?php echo esc_url(cbr_pro_addon()->plugin_dir_url(__FILE__) . 'assets/images/get-support-icon.svg'); ?>">	
										</div>
										<div class="woocommerce-list__item-text">
											<span class="woocommerce-list__item-title">
												<div class="woocommerce-list-Text">Get Support</div>
											</span>
										</div>
										<div class="woocommerce-list__item-after">
											<span class="dashicons dashicons-arrow-right-alt2"></span>
										</div>
									</a>
								</li>            
								<li class="woocommerce-list__item has-action">
									<a href="https://www.zorem.com/docs/country-based-restrictions-for-woocommerce/?utm_source=wp-admin&utm_medium=CBRDOCU&utm_campaign=add-ons" class="woocommerce-list__item-inner" target="_blank">
										<div class="woocommerce-list__item-before">
											<img src="<?php echo esc_url(cbr_pro_addon()->plugin_dir_url(__FILE__) . 'assets/images/documentation-icon.svg'); ?>">
										</div>
										<div class="woocommerce-list__item-text">
											<span class="woocommerce-list__item-title">
												<div class="woocommerce-list-Text">Documentation</div>
											</span>
										</div>
										<div class="woocommerce-list__item-after">
											<span class="dashicons dashicons-arrow-right-alt2"></span>
										</div>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>	
		</div>
		<div class="woocommerce cbr_admin_layout">
			<div class="cbr_admin_content">
			<?php
				$subscription_status = cbr_pro_addon()->license->check_subscription_status();
				if ( $subscription_status ) { ?>
					<input id="tab1" type="radio" name="tabs" class="cbr_tab_input" data-tab="settings" data-label="<?php esc_html_e( 'Settings', 'woocommerce' ); ?>" checked>
					<label for="tab1" class="cbr_tab_label first_label" ><?php esc_html_e( 'Settings', 'woocommerce' ); ?></label>
					<input id="tab2" type="radio" name="tabs" class="cbr_tab_input"  data-tab="catalog-restrictions" data-label="<?php esc_html_e( 'Catalog Restrictions', 'country-base-restrictions-pro-addon' ); ?>" <?php if ( 'catalog-restrictions' == $tab ) { echo 'checked'; } ?>>
					<label for="tab2" class="cbr_tab_label"><?php esc_html_e( 'Catalog Restrictions', 'country-base-restrictions-pro-addon' ); ?></label>
					<input id="tab5" type="radio" name="tabs" class="cbr_tab_input"  data-tab="payment-restrictions" data-label="<?php esc_html_e( 'Payment Restrictions', 'country-base-restrictions-pro-addon' ); ?>" <?php if ( 'payment-restrictions' == $tab ) { echo 'checked'; } ?>>
					<label for="tab5" class="cbr_tab_label"><?php esc_html_e( 'Payment Restrictions', 'country-base-restrictions-pro-addon' ); ?></label>
					<input id="tab4" type="radio" name="tabs" class="cbr_tab_input" data-tab="license" data-label="<?php esc_html_e( 'License', 'country-base-restrictions-pro-addon' ); ?>" <?php if ( 'license' == $tab ) { echo 'checked'; } ?>>
					<label for="tab4" class="cbr_tab_label" ><?php esc_html_e( 'License', 'country-base-restrictions-pro-addon' ); ?></label>
					<div class="menu_devider"></div>
					<?php require_once( 'views/cbr_setting_tab.php' ); ?>
					<?php require_once( 'views/cbr_bulk_restriction_tab.php' ); ?>
					<?php require_once( 'views/cbr_checkout_restriction_tab.php' ); ?>
					<?php require_once( 'views/cbr_addons_tab.php' ); ?>
				<?php } else { ?>
					<input id="tab4" type="radio" name="tabs" class="cbr_tab_input" data-tab="license" data-label="<?php esc_html_e( 'License', 'country-base-restrictions-pro-addon' ); ?>" checked>
					<label for="tab4" class="cbr_tab_label" ><?php esc_html_e( 'License', 'country-base-restrictions-pro-addon' ); ?></label>
					<div class="menu_devider"></div>
					<?php require_once( 'views/cbr_addons_tab.php' ); ?>
				<?php } ?>
			</div>
		</div>
	   <?php
	}
	
	/*
	* settings form save for Setting tab
	*
	* @since  2.4
	*/
	function cbr_setting_form_update_callback() {			
		
		if ( ! empty( $_POST ) && check_admin_referer( 'cbr_setting_form_action', 'cbr_setting_form_nonce_field' ) ) {
			
			$data = $this->get_general_settings();						
			
			foreach ( $data as $key => $val ) {				
				if ( isset( $_POST[ $key ] ) ) {						
					update_option( $key, wc_clean( $_POST[ $key ] ) );
				}
			}
			
			$data2 = $this->get_visibility_message_settings();						
			
			foreach ( $data2 as $key => $val ) {				
				if ( isset( $_POST[ $key ] ) ) {						
					update_option( $key, wc_clean($_POST[ $key ] ) );
				}
			}
			
			$data3 = $this->get_hide_completely_settings();						
			
			foreach ( $data3 as $key => $val ){				
				if ( isset( $_POST[ $key ] ) ){						
					update_option( $key, wc_clean($_POST[ $key ] ) );
				}
			}
			
			update_option( 'product_visibility', sanitize_text_field( $_POST[ 'product_visibility' ] ) );
			
			if ( 'hide_catalog_visibility' == $_POST[ 'product_visibility' ] ) {
				update_option( 'wpcbr_hide_restricted_product_variation', sanitize_text_field( $_POST[ 'wpcbr_hide_restricted_product_variation1' ] ) );
				update_option( 'wpcbr_make_non_purchasable', sanitize_text_field( $_POST[ 'wpcbr_make_non_purchasable' ] ) );
				if( '1' == $_POST[ 'wpcbr_make_non_purchasable' ] ){
					update_option( 'wpcbr_hide_product_price', sanitize_text_field( $_POST[ 'wpcbr_hide_product_price1' ] ) );
				}
			}
			if ( 'show_catalog_visibility' == $_POST[ 'product_visibility' ] ) {
				update_option( 'wpcbr_hide_restricted_product_variation', sanitize_text_field( $_POST[ 'wpcbr_hide_restricted_product_variation2' ] ) );
				update_option( 'wpcbr_hide_product_price', sanitize_text_field( $_POST[ 'wpcbr_hide_product_price2' ] ) );
				update_option( 'wpcbr_allow_product_addtocart', sanitize_text_field( $_POST[ 'wpcbr_allow_product_addtocart' ] ) );
			}
			echo json_encode( array( 'success' => 'true' ) );die();
		}
	}
	
	/**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
	 * @since  2.4
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
	function get_general_settings() {
		
        $settings = array(
			'wpcbr_force_geo_location' => array(
				'title'		=> esc_html( 'Force Geolocation', 'country-base-restrictions-pro-addon' ),
				'type'		=> 'checkbox',
				'default'	=> 'no',
				'show'		=> true,
				'id'		=> 'wpcbr_force_geo_location',
				'class'		=> 'checkbox-left',
				'label'		=> 'Enable plugin',
				'tooltip'		=> esc_html( "Enable this option to detect the customer country only by the WooCommerce geo-location and to ignore the customer shipping country (if logged in)", 'country-base-restrictions-pro-addon' ),
			),
			'wpcbr_debug_mode' => array(
				'title'		=> esc_html( 'Enable Debug Toolbar', 'country-base-restrictions-pro-addon' ),
				'type'		=> 'checkbox',
				'default'	=> 'no',
				'show'		=> true,
				'id'		=> 'wpcbr_debug_mode',
				'class'		=> 'checkbox-left',
				'label'		=> 'Enable plugin',
				'tooltip'		=> esc_html( "Enable this option to show detected geo-location country top of header in frontend.", 'country-base-restrictions-pro-addon' ),
			),
        );
        return  $settings;
    }
	
	/**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
	 * @since  2.4
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
	function get_visibility_message_settings() {
		
        $settings = array(
			'wpcbr_default_message' => array(
				'title'		=> esc_html( 'Product restriction message', 'country-base-restrictions-pro-addon' ),
				'tooltip'	=> esc_html( "This message show on product page when product is not purchasable. Default message : Sorry, this product is not available in your country.", 'country-base-restrictions-pro-addon' ),
				'placeholder'	=> esc_html( "Sorry, this product is not available to purchase in your country.", 'country-base-restrictions-pro-addon' ),
				'type'		=> 'textarea',
				'show'		=> true,
				'id'		=> 'wpcbr_default_message',
				'class'		=> '',
			),
			'wpcbr_message_position' => array(
				'title'		=> esc_html( 'Product restriction message position', 'country-base-restrictions-pro-addon' ),
				'tooltip'	=> esc_html( "Default : After add to cart. This message will show on product page when product is not purchasable.", 'country-base-restrictions-pro-addon'),
				'desc_tip'	=> esc_html( "Use the shortcode [cbr_message_position] in your product template.", 'country-base-restrictions-pro-addon' ),
				'type'		=> 'dropdown',
				'show'		=> true,
				'id'		=> 'wpcbr_message_position',
				'class'		=> '',
				'default'	=> '33',
				'options'	=> array(
					'3'			=> esc_html( 'Before title', 'country-base-restrictions-pro-addon' ),
					'8'			=> esc_html( 'After title', 'country-base-restrictions-pro-addon' ),
					'13'		=> esc_html( 'After price', 'country-base-restrictions-pro-addon' ),
					'23'		=> esc_html( 'After short description', 'country-base-restrictions-pro-addon' ),
					'33'		=> esc_html( 'After add to cart', 'country-base-restrictions-pro-addon' ),
					'43'		=> esc_html( 'After meta', 'country-base-restrictions-pro-addon' ),
					'53'		=> esc_html( 'After sharing', 'country-base-restrictions-pro-addon' ),
					'custom_shortcode'		=> esc_html( 'Use shortcode', 'country-base-restrictions-pro-addon' ),
				)
			),
			'wpcbr_cart_message' => array(
				'title'		=> esc_html( 'Cart restriction message', 'country-base-restrictions-pro-addon' ),
				'tooltip'	=> esc_html( "This message show on cart page when product is not purchasable.", 'country-base-restrictions-pro-addon' ),
				'desc_tip'	=> esc_html( "Available variable: {Product_Name}, {Product_name_with_link}", 'country-base-restrictions-pro-addon' ),
				'placeholder'	=> esc_html( "{Product_Name} has been removed from your cart since it is not available for purchase to your Country.", 'country-base-restrictions-pro-addon' ),
				'type'		=> 'textarea',
				'show'		=> true,
				'id'		=> 'wpcbr_cart_message',
				'class'		=> '',
			),
			'cbr_cat_default_message' => array(
				'title'		=> esc_html( 'Category restriction message', 'country-base-restrictions-pro-addon' ),
				'tooltip'	=> esc_html( "This message show on the category/tag page when a category/tag is restricted. Default message: Sorry, this category's/tag's product is not available in your country.", 'country-base-restrictions-pro-addon' ),
				'placeholder'	=> esc_html( "Sorry,  products from this category are not available to purchase in your country.", 'country-base-restrictions-pro-addon' ),
				'desc_tip'	=> esc_html( "You can use shortcode [cbr_category_message] in your category template.", 'country-base-restrictions-pro-addon' ),
				'type'		=> 'textarea',
				'show'		=> true,
				'id'		=> 'cbr_cat_default_message',
				'class'		=> '',
			)
        );
        return  $settings;
    }
	
	/**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
	 * @since  2.4
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
	function get_hide_completely_settings() {
		$page_list = wp_list_pluck( get_pages(), 'post_title', 'ID' );
        $settings = array(
			'wpcbr_redirect_404_page' => array(
			  'type'		=> 'checkbox',
			  'title'		=> esc_html( 'Redirect the 404 page', 'country-base-restrictions-pro-addon' ),				
			  'show'		=> true,
			  'default'		=> 'no',
			  'class'     	=> 'pro-feature',
			  'id'			=> 'wpcbr_redirect_404_page',
			  'label'		=> 'Enable plugin',
			  'tooltip'     => esc_html( 'Enable this option to redirect 404 error page to shop page.','country-base-restrictions-pro-addon'),
		  ),
		  'wpcbr_choose_the_page_to_redirect' => array(
			  'type'		=> 'dropdown',
			  'title'		=> esc_html( 'Choose the page to redirect', 'country-base-restrictions-pro-addon' ),				
			  'show'		=> true,
			  'default'		=> '',
			  'class'     	=> 'pro-feature',
			  'id'			=> 'wpcbr_choose_the_page_to_redirect',
			  'label'		=> 'Enable plugin',
			  'options'		=> $page_list,
			  'tooltip'     => esc_html( 'Choose the page for redirect 404 error page to selected page.','country-base-restrictions-pro-addon'),
		  ),
        );
        return  $settings;
    }

	/**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
	 * @since  2.4
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
	function get_product_settings() {
        
		$settings = array(
			'wpcbr_hide_restricted_product_variation1' => array(
				'title'		=> esc_html( 'Hide Product Variations', 'country-base-restrictions-pro-addon' ),
				'type'		=> 'checkbox',
				'default'	=> 'no',
				'show'		=> true,
				'id'		=> 'wpcbr_hide_restricted_product_variation',
				'class'		=> '',
				'label'		=> 'Enable plugin',
				'tooltip'	=> esc_html( 'Enable this option to hide the restricted product variations form the product variations selection on variable product page.', 'country-base-restrictions-pro-addon' ),
			),
			'wpcbr_hide_product_price1' => array(
				'title'		=> esc_html( 'Hide Restricted Product Price', 'country-base-restrictions-pro-addon' ),
				'default'	=> 'no',
				'show'		=> true,
				'type'		=> 'checkbox',
				'id'		=> 'wpcbr_hide_product_price',
				'class'		=> '',
				'label'		=> 'Enable plugin',
				'tooltip'		=> esc_html( "Enable this option to hide restricted product price.", 'country-base-restrictions-pro-addon' ),
			),
			'wpcbr_make_non_purchasable' => array(
				'title'		=> esc_html( 'Make non-purchasable', 'country-base-restrictions-pro-addon' ),
				'type'		=> 'checkbox',
				'default'	=> 'no',
				'show'		=> true,
				'id'		=> 'wpcbr_make_non_purchasable',
				'class'		=> '',
				'label'		=> 'Enable plugin',
				'tooltip'	=> esc_html( "Enable this option to make products non-purchasable (i.e. product can't be added to the cart).", 'country-base-restrictions-pro-addon' ),
			),
        );
        return  $settings;
    }
	
	/**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
	 * @since  2.4
     * @return array Array of settings for @see woocommerce_admin_fields() function.
	*/
	function get_product_catelog_settings() {
        
		$settings = array(
		'wpcbr_hide_product_price2' => array(
				'title'		=> esc_html( 'Hide Restricted Product Price', 'country-base-restrictions-pro-addon' ),
				'default'	=> 'no',
				'show'		=> true,
				'type'		=> 'checkbox',
				'id'		=> 'wpcbr_hide_product_price',
				'class'		=> '',
				'label'		=> 'Enable plugin',
				'tooltip'	=> esc_html( "Enable this option to hide restricted product price.", 'country-base-restrictions-pro-addon' ),
			),
		'wpcbr_hide_restricted_product_variation2' => array(
				'title'		=> esc_html( 'Hide Product Variations', 'country-base-restrictions-pro-addon' ),
				'type'		=> 'checkbox',
				'default'	=> 'no',
				'show'		=> true,
				'id'		=> 'wpcbr_hide_restricted_product_variation',
				'class'		=> '',
				'label'		=> 'Enable plugin',
				'tooltip'	=> esc_html( "Enable this option to hide the restricted product variations form the product variations selection on variable product page.", 'country-base-restrictions-pro-addon' ),
			),
		'wpcbr_allow_product_addtocart' => array(
				'title'		=> esc_html( 'Allow Add To Cart', 'country-base-restrictions-pro-addon' ),
				'type'		=> 'checkbox',
				'default'	=> 'no',
				'show'		=> true,
				'id'		=> 'wpcbr_allow_product_addtocart',
				'class'		=> '',
				'label'		=> 'Enable plugin',
				'tooltip'	=> esc_html( "Enable this option to allow add to cart the restricted products but the customer can't process to checkout.", 'country-base-restrictions-pro-addon' ),
			),
        );
 
		return  $settings;
    }
	
	/*
	* get html of fields
	* 
	* @since  2.4
	*/
	public function get_html_general_setting( $arrays ) {
		
		$checked = '';
		foreach ( (array)$arrays as $id => $array ) {
			if ( $array['show'] ) {
				$desc = isset( $array[ 'desc' ] ) ? $array[ 'desc' ] : '' ;
				$desc_tip = isset( $array[ 'desc_tip' ] ) ? $array[ 'desc_tip' ] : '' ;
				if ( 'checkbox-left' == $array['class'] ) { ?>
					<tr valign="top" class="border_1 <?php echo $array['class']; ?>">
						<?php 
						if ( isset( $array['id'] ) && get_option( $array['id'] ) ) {
							$checked = 'checked';
						} else {
							$checked = '';
						} 
						?>					
						<th scope="row" class="titledesc" colspan="2">
							<label class="checkbx-label" for="<?php echo $id; ?>">
								<input type="hidden" name="<?php echo $id; ?>" value="0">
								<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $id; ?>" class="checkbox-input" <?php echo $checked; ?> value="1">
								<?php echo $array[ 'title' ]; ?><?php if ( isset( $array[ 'title_link' ] ) ) { echo $array[ 'title_link' ]; } ?>
								<?php if ( isset( $array[ 'tooltip' ] ) ) { ?>
									<span class="woocommerce-help-tip tipTip" title="<?php echo $array[ 'tooltip' ]; ?>"></span>
								<?php } ?>
							</label>
						</th>
					</tr>
				<?php } elseif ( 'textarea' == $array[ 'type' ] ) { ?>
					<tr valign="top" class="border_1 <?php echo $array[ 'class' ]; ?>">
						<th scope="row" class="titledesc">
							<label for="<?php echo $id; ?>">
								<?php echo $array[ 'title' ]; ?><?php if ( isset( $array[ 'title_link' ] ) ) { echo $array[ 'title_link' ]; } ?>
								<?php if ( isset( $array[ 'tooltip' ] ) ) { ?>
									<span class="woocommerce-help-tip tipTip" title="<?php echo $array[ 'tooltip' ]; ?>"></span>
								<?php } ?>
							</label>
						</th>
						<td class="forminp"  <?php if ( 'desc' == $array[ 'type' ] ) { ?> colspan=2 <?php } ?>>
							<fieldset>
							<textarea rows="3" cols="20" class="input-text regular-input" type="textarea" name="<?php echo $id; ?>" id="<?php echo $id; ?>" style="" placeholder="<?php if ( !empty( $array[ 'placeholder' ] ) ) { echo $array[ 'placeholder' ]; } ?>"><?php if ( !empty( get_option( $array[ 'id' ] ) ) ) { echo stripslashes( get_option( $array[ 'id' ] ) ); } ?></textarea>
							</fieldset><p class="description"><?php echo $desc_tip; ?></p>
						</td>
					</tr>
				<?php }  elseif ( isset( $array[ 'type' ] ) && 'dropdown' == $array[ 'type' ] ) { ?>
					<tr valign="top" class="border_1 <?php echo $array[ 'class' ]; ?>">
						<th scope="row" class="titledesc" >
							<label for="<?php echo $id; ?>">
								<?php echo $array[ 'title' ]; ?><?php if ( isset( $array[ 'title_link' ] ) ) { echo $array[ 'title_link' ]; } ?>
								<?php if ( isset( $array[ 'tooltip' ] ) ) { ?>
									<span class="woocommerce-help-tip tipTip" title="<?php echo $array[ 'tooltip' ]; ?>"></span>
								<?php } ?>
							</label>
						</th>
						<td class="forminp"  <?php if ( 'desc' == $array['type'] ) { ?> colspan=2 <?php } ?>>
						<?php
							if ( isset( $array[ 'multiple' ] ) ) {
								$multiple = 'multiple';
								$field_id = $array[ 'multiple' ];
							} else {
								$multiple = '';
								$field_id = $id;
							}
						?>
						<fieldset>
							<select class="select select2" id="<?php echo $field_id; ?>" name="<?php echo $id; ?>" <?php echo $multiple; ?>>
								<?php foreach ( (array)$array[ 'options' ] as $key => $val ) { ?>
									<?php
										$selected = '';
										if ( isset( $array[ 'multiple' ] ) ) {
											if ( in_array( $key, (array)$this->data->$field_id ) ) { 
												$selected = 'selected';
											}
										} else {
											if ( get_option( $array[ 'id' ] ) == (string)$key ) {
												$selected = 'selected';
											}
										}
									?>
									<option value="<?php echo $key; ?>" <?php echo $selected; ?> ><?php echo $val; ?></option>
								<?php } ?><p class="description"><?php echo $desc; ?></p>
							</select><p class="description"><?php echo $desc_tip; ?></p>
						</fieldset>
						</td>
					</tr>
				<?php }
			}
		}
	}

    /*
	* get html of fields
	*
	* @since  2.4
	*/
	public function get_html( $arrays ) {
		
		$checked = '';
		?>
		<table class="form-table">
			<tbody>
            	<?php foreach ( (array)$arrays as $id => $array ) {
					if ( $array[ 'show' ] ) {
						$desc = isset( $array[ 'desc' ] ) ? $array[ 'desc' ] : '' ;
						$desc_tip = isset( $array[ 'desc_tip' ] ) ? $array[ 'desc_tip' ] : '' ;
						
						if ( 'title' == $array[ 'type' ] ) { ?>
							<tr valign="top titlerow">
								<th colspan="2"><h3><?php echo $array[ 'title' ]; ?></h3></th>
							</tr>    	
                    		<?php continue;
						} ?>
						<tr valign="top" class="<?php echo $array[ 'class' ]; ?> border_1">
							<?php if ( 'desc' != $array[ 'type'] && isset( $array[ 'title' ] ) ) { ?>										
								<th scope="row" class="titledesc"  >
									<label for=""><?php echo $array[ 'title' ]; ?><?php if ( isset( $array[ 'title_link' ] ) ) { echo $array[ 'title_link' ]; } ?>
										<?php if( isset( $array[ 'tooltip' ] ) ) { ?>
											<span class="woocommerce-help-tip tipTip" title="<?php echo $array[ 'tooltip' ]; ?>"></span>
										<?php } ?>
									</label>
								</th>
							<?php } ?>
							<td class="forminp"  <?php if ( 'desc' == $array[ 'type' ] ) { ?> colspan=2 <?php } ?>>
								<?php if ( 'checkbox' == $array[ 'type' ] ) {								
									if ( isset( $array[ 'id' ] ) && get_option( $array[ 'id' ] ) ) {
										$checked = 'checked';
									} else{
										$checked = '';
									} 
									
									if( isset( $array[ 'disabled' ] ) && true == $array[ 'disabled' ] ){
										$disabled = 'disabled';
										$checked = '';
									} else {
										$disabled = '';
									}							
									?>
									<?php if ( 'toggle' == $array[ 'class' ] ) { ?>
										<input type="hidden" name="<?php echo $id?>" value="0"/>
										<input class="tgl tgl-flat-cev" id="<?php echo $id; ?>" name="<?php echo $id; ?>" type="checkbox" <?php echo $checked; ?> value="1" <?php echo $disabled; ?>/>
										<label class="tgl-btn" for="<?php echo $id; ?>"></label>
										<p class="description"><?php echo $desc; ?></p>
									<?php } else { ?>
										<span class="checkbox">
											<label class="checkbx-label" for="<?php echo $id; ?>">
												<input type="hidden" name="<?php echo $id; ?>" value="0"/>
												<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $id; ?>" class="checkbox-input" <?php echo $checked; ?> value="1" <?php echo $disabled; ?>/>
											</label><p class="description"><?php echo $desc; ?></p>
										</span>
									<?php } ?>
								<?php } elseif ( 'textarea' == $array[ 'type' ] ) { ?>
									<fieldset>
										<textarea rows="3" cols="20" class="input-text regular-input" type="textarea" name="<?php echo $id; ?>" id="<?php echo $id; ?>" style="" placeholder="<?php if ( !empty( $array[ 'placeholder' ] ) ) { echo $array[ 'placeholder' ]; } ?>"><?php if ( !empty( get_option( $array[ 'id' ] ) ) ) { echo stripslashes( get_option( $array[ 'id' ] ) ); }?></textarea>
									</fieldset>
								<?php } elseif ( 'color' == $array[ 'type' ] ) { ?>
									<fieldset>
										  <input name="<?php echo $id; ?>" type="text" id="<?php echo $id; ?>" value="<?php echo get_option( $array[ 'id' ] )?>">
										<span class="slider round"></span>
									</fieldset>
								<?php }  elseif ( isset( $array[ 'type' ] ) && 'dropdown' == $array[ 'type' ] ) { ?>
									<?php
										if ( isset( $array[ 'multiple' ] ) ) {
											$multiple = 'multiple';
											$field_id = $array[ 'multiple' ];
										} else {
											$multiple = '';
											$field_id = $id;
										}
									?>
									<fieldset>
										<select class="select select2" id="<?php echo $field_id; ?>" name="<?php echo $id; ?>" <?php echo $multiple; ?>>
											<?php foreach ( (array)$array[ 'options' ] as $key => $val ) { ?>
												<?php
													$selected = '';
													if( isset($array[ 'multiple' ] ) ){
														if ( in_array( $key, (array)$this->data->$field_id ) ) {
															$selected = 'selected';
														}
													} else {
														if ( get_option( $array[ 'id' ] ) == (string)$key ) { 
															$selected = 'selected';
														}
													}
												?>
												<option value="<?php echo $key; ?>" <?php echo $selected; ?> ><?php echo $val; ?></option>
											<?php } ?>
											<p class="description"><?php echo $desc; ?></p>
										</select><p class="description"><?php echo $desc_tip; ?></p>
									</fieldset>
								<?php } elseif ( 'label' == $array[ 'type' ] ) { ?>
									<fieldset>
									   <label><?php echo $array[ 'value' ]; ?></label>
									</fieldset>
								<?php } elseif ( 'button' == $array[ 'type' ] ) { ?>
									<fieldset>
										<button class="button-primary btn_green2 <?php echo $array[ 'button_class' ];?>" <?php if ( $array[ 'disable' ]  == 1 ) { echo 'disabled'; } ?>><?php echo $array[ 'label' ];?></button>
									</fieldset>
								<?php } elseif ( 'radio' == $array[ 'type' ] ) { ?>
									<fieldset>
										<ul>
											<?php foreach ( (array)$array[ 'options' ] as $key => $val ) { ?>
												<li><label><input name="product_visibility" value="<?php echo $key; ?>" type="radio" style="" class="product_visibility" <?php if ( get_option( $array[ 'id' ] ) == $key ) { echo 'checked'; } ?> /><?php echo $val; ?><br></label></li>
											<?php } ?>
										</ul>
									</fieldset>
								<?php } else { ?>		
									<fieldset>
										<input class="input-text regular-input " type="text" name="<?php echo $id; ?>" id="<?php echo $id; ?>" style="" value="<?php echo get_option( $array[ 'id' ] )?>" placeholder="<?php if ( !empty( $array[ 'placeholder' ] ) ) {echo $array[ 'placeholder' ]; } ?>">
									</fieldset>
								<?php } ?>
							</td>
						</tr>
					<?php } 
				} ?>
			</tbody>
		</table>
		<?php 
	}
	
	/*
	* add bulk actions option function
	*
	* @since  1.0
	* WC Product bulk actions
	*/
	function add_cbr_remove_rule_bulk_actions( $bulk_array ) {
	 
		$bulk_array[ 'remove_cbr_rule' ] = 'Remove CBR Rule';
		return $bulk_array;
	 
	}
	
	/*
	* bulk actions handler function
	*
	* @since  1.0
	* WC bulk actions
	*/
	function remove_cbr_rule_bulk_action_handler( $redirect, $doaction, $object_ids ) {
 
		// let's remove query args first
		$redirect = remove_query_arg( array( 'removed_cbr_rules' ), $redirect );
	 
		// do something for "Remove CBR Rule" bulk action
		if ( 'remove_cbr_rule' == $doaction ) {
			foreach ( $object_ids as $post_id ) {
				update_post_meta( $post_id, '_fz_country_restriction_type', 'all' );
				update_post_meta( $post_id, '_restricted_countries', array() );
			}
			$redirect = add_query_arg( 'removed_cbr_rules', count( $object_ids ), $redirect );
		}
	 
		return $redirect;
	 
	}
	
	/*
	* bulk actions notices function
	*
	* @since  1.0
	* WC bulk actions
	*/
	function cbr_removed_rule_bulk_action_notices() {
 
		// but you can create an awesome message
		if ( ! empty( $_REQUEST[ 'removed_cbr_rules' ] ) ) {
	 
			// depending on ho much posts were changed, make the message different
			printf( '<div id="message" class="updated notice is-dismissible"><p>' .
				_n( 'Country Restrictions rules have been removed from %s product.',
				'Country Restrictions rules have been removed from %s products.',
				intval( $_REQUEST[ 'removed_cbr_rules' ] )
			) . '</p></div>', intval( wc_clean( $_REQUEST[ 'removed_cbr_rules' ] ) ) );
	 
		}
	 
	}
	
	/*
	* ajax call for save bulk restriction rules 
	*
	* @since  1.0 
	*/
	public function save_cbr_bulk_restrictions_callback() {

		$bulk_data = isset( $_POST[ 'data' ] ) ? wc_clean( $_POST[ 'data' ] ) : array();
		
		if ( check_admin_referer( 'cbr_bulk_restrictions_form_action', 'cbr_bulk_restrictions_form_nonce_field' ) ) {
			
			update_option( "cbr_bulk_restrictions", $bulk_data );
						
			echo json_encode( array( 'success' => 'true' ) );die();
			
		}
	}
	
	/*
	* ajax call for save checkout restriction rules 
	*
	* @since  1.0 
	*/
	public function save_cbr_checkout_restrictions_callback() {

		$checkout_data = isset( $_POST[ 'data' ] ) ? wc_clean( $_POST[ 'data' ] ) : array();

		if ( check_admin_referer( 'cbr_checkout_restrictions_form_action', 'cbr_checkout_restrictions_form_nonce_field' ) ) {

			update_option( "cbr_checkout_restrictions", $checkout_data );

			echo json_encode( array( 'success' => 'true' ) );die();

		}
	}
	
	/*
	* ajax call for save Gayment gateway settings
	*
	* @since  1.0
	*/
	public function save_cbr_payment_gateway_callback() {

		if ( ! empty( $_POST ) && check_admin_referer( 'cbr_payment_gateway_form_action', 'cbr_payment_gateway_form_nonce_field' ) ) {

			update_option( "cbr_payment_gateway_methods", wc_clean( $_POST[ 'cbr_payment_gateway' ] ) );

			echo json_encode( array( 'success' => 'true' ) );die();

		}
	}
	
	/*
	* callback for Pro WC Product Meta EXport
	*
	* @since  1.0
	*/
	function prepare_cbr_meta_for_export( $meta_value, $meta, $product, $row ) {

		if ( '_restricted_countries' == $meta->key ) {
			$meta_value = implode( ',', $meta_value );
		}
		if ( '_fz_country_restriction_type' == $meta->key ) {
			if ( 'all' == $meta->value ) {
				$meta_value = '0';
			}
			if ( 'specific' == $meta->value ) {
				$meta_value = '1';
			}
			if ( 'excluded' == $meta->value ) {
				$meta_value = '2';
			}
		}

		return $meta_value;
	}
	
	/*
	* callback for Pro WC Product Meta Import
	*
	* @since  1.0
	*/
	function prepare_cbr_meta_for_import( $object, $data ) {
		
		foreach ( $data[ 'meta_data' ] as $key => $meta) {
			
			if ( '_restricted_countries' == $meta[ 'key' ] ) {
				$meta[ 'value' ] = strtoupper( $meta[ 'value' ] );
				$meta[ 'value' ] = str_replace( ' ', '', $meta[ 'value' ] );
				$meta_value = explode( ",", $meta[ 'value' ] );
				$object->update_meta_data( '_restricted_countries', $meta_value );
			}
			
			if ( '_fz_country_restriction_type' == $meta[ 'key' ] ) {
				if ( '0' == $meta[ 'value' ] ) {
					$meta[ 'value' ] = 'all';
				}
				if ( '1' == $meta[ 'value' ] ) {
					$meta[ 'value' ] = 'specific';
				}
				if ( '2' == $meta[ 'value' ] ) {
					$meta[ 'value' ] = 'excluded';
				}
				$meta_value = $meta[ 'value' ];
				$object->update_meta_data( '_fz_country_restriction_type', $meta_value );
			}
		}		
		return $object;
	}
	
	/*
	* Run preview cbr widget 
	*
	* @since  2.0
	*/
	public function run_preview_func() {
		
		$action = isset( $_REQUEST[ "action" ] ) ? $_REQUEST[ "action" ] : "";
		
		if ( 'preview_cbr_widget_lightbox' != $action ) {
			return;
		}
		
		get_header();
		
		the_content();
		
		wp_enqueue_style( 'cbr-pro-front-css', cbr_pro_addon()->plugin_dir_url() . 'assets/css/front.css', array(), cbr_pro_addon()->version );
		wp_dequeue_script( 'cbr-pro-front-js' );
		
		$countries = WC()->countries->get_shipping_countries();
		
		$location = WC_Geolocation::geolocate_ip();
		
		$country = $location[ 'country' ];
		$cookie_country = !empty( $_COOKIE[ "country" ] ) ? sanitize_text_field( $_COOKIE[ "country" ] ) : $country;
		
		if ( !empty( $cookie_country ) ) { $country = WC()->countries->countries[ $cookie_country ]; }
		
		$cbrw_popup_header = !empty( get_option( 'cbrwl_header_text' ) ) ? get_option( 'cbrwl_header_text', 'Choose Shipping Country' ) : 'Choose Shipping Country';
		$display_text = !empty( get_option( 'cbrw_label_text' ) ) ? get_option( 'cbrw_label_text', 'Delivery to' ) : 'Delivery to';
		$text_align = is_rtl() ? get_option( 'cbrwl_text_align', 'right' ) : get_option( 'cbrwl_text_align', 'left' );

		?>
		<style>
			html{overflow: hidden;}
			.cbr-widget-popup .select2-container--open .select2-dropdown {top: 0;}
			.customize-partial-edit-shortcut-button {display: none;}
			.cbr-widget-popup .select2-container .select2-selection {height: 34px;border-radius: 5px;}
			.popupwrapper{background:<?php echo cbr_pro_widget::get_instance()->hex2rgba( get_option( 'cbrwl_background_color', '' ), get_option( 'cbrwl_background_opacity', '0.7' ) ); ?> !important;}
			.popuprow{background:<?php echo get_option( 'cbrwl_box_background_color', '#eeeeee' );?> !important;border-color:<?php echo get_option( 'cbrwl_box_border_color', '#e0e0e0' ); ?> !important;text-align:<?php echo $text_align; ?> !important;width:<?php echo get_option( 'cbrwl_box_width', '400' ); ?>px !important;padding:<?php echo get_option( 'cbrwl_box_padding', '20' ); ?>px !important;border-radius: <?php echo get_option( 'cbrwl_box_border_redius', '5' ); ?>px !important;}
		</style>
		
		<div id="" class="popupwrapper cbr-widget-popup" style="">
			<div class="popuprow" style="">
				<div class="popup_header" style="">
					<h4 class="popup_title"><?php echo $cbrw_popup_header; ?></h4>
				</div>
				<div class="popup_body" style="">
					<p style=""><?php echo wp_kses_post( stripslashes( get_option( 'cbrwl_text_before_dropdown', '' ) ), 'country-base-restrictions-pro-addon' ); ?></p>
					<?php 
					asort( $countries );
					?>
					<div class="popup_content">
					<select id="cbr_widget_select" class="select2 cbr-select2 widget-country">
						<option value=""><?php echo esc_html_e( 'Select Country', 'country-base-restrictions-pro-addon' ); ?></option>
						<?php foreach ( $countries as $key => $val ) { ?>
							 <option value="<?php echo $key;?>" <?php if(isset($_COOKIE["country"]) && $_COOKIE["country"] == $key){echo 'selected';}?>><?php echo $val; ?></option>
						<?php } ?>
					</select>
					<button type="button" class="button primary-button" onclick="setCountryCookie('country', document.getElementById('cbr_widget_select').value, 365)">Apply</button>
					</div>
					<p><?php echo wp_kses_post( stripslashes( get_option( 'cbrwl_text_after_dropdown', '' ) ), 'country-base-restrictions-pro-addon'  ); ?></p>
		</div>
			<div class="popupclose"></div>
		</div>
		<?php
		wp_footer();
		exit;
	}
}
