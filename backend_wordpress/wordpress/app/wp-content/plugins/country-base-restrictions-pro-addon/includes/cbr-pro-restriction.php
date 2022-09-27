<?php
/**
 * CBR_PRO_Restriction 
 *
 * @class   CBR_PRO_Restriction
 * @package WooCommerce/Classes
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CBR_PRO_Restriction class
 *
 * @since  1.0
 */
class CBR_PRO_Restriction {

	/**
	 * Get the class instance
	 *
	 * @since  1.0
	 * @return CBR_PRO_Restriction
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
	public function init(){
		
		//adding hooks
		//callback on activate plugin
		register_activation_hook( __FILE__, array( $this, 'on_activation' ) );
		
		//hook for geolocation_update_database	
		add_filter( 'woocommerce_maxmind_geolocation_update_database_periodically', array( $this, 'update_geo_database' ), 10, 1 );
		
		if ( 'hide_completely' == get_option( 'product_visibility' ) || ( '1' == get_option( 'wpcbr_make_non_purchasable' ) && 'hide_catalog_visibility' == get_option( 'product_visibility' ) ) || 'show_catalog_visibility' == get_option( 'product_visibility' ) ) {				
			add_filter( 'woocommerce_is_purchasable', array( $this, 'is_purchasable' ), 1, 2 );
			add_filter( 'woocommerce_variation_is_purchasable', array( $this, 'is_purchasable' ), 1, 2 );
			
			add_filter( 'woocommerce_available_variation', array( $this, 'variation_filter' ), 10, 3 );
			add_action( 'woocommerce_after_checkout_validation', array( $this, 'after_checkout_validation' ), 10, 2);
			add_filter( 'woocommerce_get_item_data', array( $this, 'add_restriction_message_in_cart_item' ), 10, 2 );
			
			//subscription variation item if that is restricted
			add_filter('woocommerce_subscription_variation_is_purchasable', array( $this, 'is_purchasable' ), 1, 2 );
		}
		
		$position = get_option( 'wpcbr_message_position', 33 );
		if ( 'custom_shortcode' == $position ) {
			//message position shortcode function for Elementor product
			add_shortcode( 'cbr_message_position', array( $this, 'cbr_message_position_func' ) );
		} else {
			add_action( 'woocommerce_single_product_summary', array( $this, 'meta_area_message' ), $position );
		} 
		
		//hook for pre_get_posts	
		add_action( 'pre_get_posts', array( $this, 'product_by_country_pre_get_posts' ) );
		
		add_filter( 'woocommerce_related_products',	array( $this,  'restricted_related_products' ), 10, 3 );
		
		add_filter( 'woocommerce_product_get_upsell_ids', array( $this, 'restricted_upsell_products' ), 10, 2 );
		
		//Query args modify for Visual Composser products of restricted
		add_filter( 'woocommerce_shortcode_products_query', array( $this, 'vc_product_restricted_query' ), 10, 1 );
		
		//hooks for cart item message
		add_filter( 'woocommerce_cart_item_removed_message',array( $this, 'cart_item_removed_massage' ), 10 ,2 );
		
		//callback for redirect 404 error page
		add_action( 'template_redirect', array( $this, 'redirect_404_to_homepage' ) );
		
		//callback for cart page
		add_action( 'woocommerce_calculated_shipping', array( $this, 'update_cart_and_checkout_items'), 10 );
		
		//callback for checkout page
		add_action( 'woocommerce_review_order_before_cart_contents', array( $this, 'update_cart_and_checkout_items' ), 10 );
				
		//filter for message on product page
		add_filter( 'cbr_is_restricted', array( $this, 'is_restricted_by_cbr_pro' ), 10, 2 );
		
		//filter for message on product page
		add_filter( 'cbr_cart_message',array( $this, 'add_cbr_cart_message' ), 10, 2 );
		
		//callback message for restricted category page
		add_action( 'woocommerce_no_products_found', array( $this, 'restricted_category_message' ), 5 );
		add_shortcode( 'cbr_category_message', array( $this, 'restricted_category_message' ) );
		
		//callback for disable payment gatway for restricted country
		add_filter( 'woocommerce_available_payment_gateways', array( $this, 'cbr_restrict_payment_gateway_by_country' ), 10, 1 );
		
		//callback for hide product price on product page
		add_filter( 'woocommerce_get_price_html', array( $this,'woocommerce_hide_product_price' ), 10, 2 );
						
	}
	
	/**
	 * WC_Geolocation database update hooks
	 *
	 * @since 1.0
	 *
	 */
	function on_activation() {
		WC_Geolocation::update_database();                     
	}
	
	/**
	 * update geo database
	 *
	 * @since 1.0
	 */
	function update_geo_database() {
		return true;
	}
	
	/*
	* check restricted by the product id for simple product
	*
	* @since  1.0
	*/
	function is_restricted_by_id( $id ) {
		$restriction = get_post_meta( $id, '_fz_country_restriction_type', true );

		if ( 'specific' == $restriction || 'excluded' == $restriction ) {
			
			$countries = get_post_meta( $id, '_restricted_countries', true );
			
			if ( empty( $countries ) || ! is_array( $countries ) ) {
				$countries = array();
			}

			$customercountry = $this->get_country();

			if ( 'specific' == $restriction && !in_array( $customercountry, $countries ) ) {
				return true;
			}

			if ( 'excluded' == $restriction && in_array( $customercountry, $countries ) ) {
				return true;
			}
		}

		return false;
	}
	
	/*
	* check restricted by the product id for variation
	*
	* @since  1.0
	*/
	function is_restricted( $product ) {
		
		if ( $product ) { 
			$product_id = $product->get_id();
		}
		
		if ( ( $product ) && ( 'variation' == $product->get_type() ) ) {
			$parentid = $product->get_parent_id();
			$parentRestricted = $this->is_restricted_by_id( $parentid );
			if ( $parentRestricted ) {
				return true;
			}
		}

		return $this->is_restricted_by_id($product_id);
	}
	
	/*
	* check product is_purchasable or not
	*
	* @since  1.0
	*/
	function is_purchasable( $purchasable, $product ) {		
		if ( $this->is_restricted( $product ) || apply_filters( 'cbr_is_restricted', false, $product ) ) { 
			$purchasable = false;
		}
		
		if ( 'show_catalog_visibility' == get_option( 'product_visibility' ) && '1' == get_option( 'wpcbr_allow_product_addtocart' ) && '1' != get_option('wpcbr_hide_product_price') ) {
			$purchasable = true;
		}
		
		return $purchasable;
	}
	
	/*
	* checkout validation error display
	*
	* @since  1.0
	*/
	public function after_checkout_validation( $fields, $errors ) {
		
		if ( 'show_catalog_visibility' == get_option( 'product_visibility' ) && '1' == get_option( 'wpcbr_allow_product_addtocart' ) && '1' != get_option( 'wpcbr_hide_product_price' ) ) {
			foreach ( WC()->cart->get_cart() as $item_key => $item ) {
				$product =  wc_get_product( $item['data']->get_id());
				if ( $this->is_restricted( $product ) || apply_filters( 'cbr_is_restricted', false, $product ) ) { 
					$item_name = $item['data']->get_title();
					$errors->add( 'validation', '<strong>'.$item_name.'</strong> is not available for purchase to your Country.' );
				}	
			}
		}
	}
	
	/*
	* checkout validation error display
	*
	* @since  1.0
	*/
	public function add_restriction_message_in_cart_item( $item_data, $cart_item ) {
		
		if ( 'show_catalog_visibility' == get_option( 'product_visibility' ) && '1' == get_option( 'wpcbr_allow_product_addtocart' ) && '1' != get_option( 'wpcbr_hide_product_price' ) ) {
			$product_id = !empty( $cart_item['product_id'] ) ? $cart_item['product_id'] : 0;
			$product    = wc_get_product( $product_id );
			if( $this->is_restricted( $product ) || apply_filters( 'cbr_is_restricted', false, $product ) ){
				echo $this->no_soup_for_you();
			}
		}
		
		return $item_data;
	}
	
	/*
	* check variation product filter for restriction
	*
	* @since  1.0
	*/
	function variation_filter( $data, $product, $variation ) {
		
		if ( !$data[ 'is_purchasable' ] || !$product->is_purchasable() ) {
			$data[ 'variation_description' ] = $this->no_soup_for_you() . $data[ 'variation_description' ];
			if ( '1' == get_option( 'wpcbr_hide_restricted_product_variation' ) ) {
				$data[ 'variation_is_active' ] = '';
			}
		}
		return $data;
	}
	
	/*
	* message position shortcode support for Elementor product
	*
	* @since  1.0
	*/
	function cbr_message_position_func() {
		ob_start();
		$this->meta_area_message();
  		return ob_get_clean();
	}
	
	/*
	* cbr add default_message for restricted product
	*
	* @since  1.0
	*/
	function meta_area_message() {
		global $product;

		if ( $this->is_restricted( $product ) || apply_filters( 'cbr_is_restricted', false, $product ) ) {
			if ( !$product->is_purchasable() ) {
				echo $this->no_soup_for_you();
			}
		}
	}

	/*
	* get default_message for restricted product
	*
	* @since  1.0
	*/
	function default_message() {
		return esc_html( 'Sorry, this product is not available to purchase in your country.', 'country-base-restrictions-pro-addon' );
	}        
	
	/*
	* get custom message for restricted product
	*
	* @since  1.0
	*/
	function no_soup_for_you() {
		$msg = get_option( 'wpcbr_default_message', $this->default_message() );
		if ( empty( $msg ) ) { 
			$msg = $this->default_message();
		}
		return "<p class='restricted_country'>" . stripslashes( $msg ) . "</p>";
	}
	
	/*
	* get_country
	*
	* @since  1.0
	*/
	function get_country() {
		
		if ( '1' == get_option( 'wpcbr_debug_mode' ) && is_admin() ) {
			$cookie_country = isset( $_COOKIE[ "country" ] ) ? sanitize_text_field( $_COOKIE[ "country" ] ) : '';
			if ( !empty( $cookie_country ) ) {
				return $this->user_country = $_COOKIE[ "country" ];
			}
		}
		
		$force_geoloaction = get_option( 'wpcbr_force_geo_location' );
		if ( !$force_geoloaction ) {
			global $woocommerce;
			if ( isset( $woocommerce->customer ) ) {
				$shipping_country = $woocommerce->customer->get_shipping_country();
				$cookie_country = !empty($_COOKIE[ "country" ]) ? sanitize_text_field( $_COOKIE[ "country" ] ) : $shipping_country;
				if ( isset( $cookie_country ) ) {
					$this->user_country = $cookie_country;
					return $this->user_country;
				}
			}
		}				
		
		if ( empty( $this->user_country )  ) {
			$geoloc = WC_Geolocation::geolocate_ip();
			$cookie_country = !empty( $_COOKIE[ "country" ] ) ? sanitize_text_field( $_COOKIE[ "country" ] ) : $geoloc[ 'country' ];
			$this->user_country = $cookie_country;
			
			return $this->user_country;
		}
		
		return $this->user_country;
	}
	
	/*
	* posts & category set NOT_IN and IN by query modified
	*
	* @since  1.0
	*/
	function product_by_country_pre_get_posts( $query ) {
		
		if ( is_admin() ) {
			return;
		}
		
		// when post_type is not product or not a category/shop page return 
		if ( isset( $query->query_vars[ 'post_type' ] ) && 'product' != $query->query_vars[ 'post_type' ] && !isset( $query->query_vars[ 'product_cat' ] ) ) {
			return;
		}
		
		// shop, category, search = visible, single page visible
		if ( 'show_catalog_visibility' == get_option( 'product_visibility' ) ) {
			return;
		}
		
		// shop, category, search = hidden, single page visible
		if ( 'hide_catalog_visibility' == get_option( 'product_visibility' ) && 1 == $query->is_single ){
			return;
		}
		
		//for hide completely continue
		
		remove_action( 'pre_get_posts', array( $this, 'product_by_country_pre_get_posts' ) );
		
		$post__not_in = $query->get( 'post__not_in' );
		
		$default_posts_per_page = apply_filters( 'cbr_post_per_page', get_option( 'posts_per_page', 10 ) );
		$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
		
		
		$args = $query->query_vars;
		$args[ 'fields' ] = 'ids';
		$args[ 'posts_per_page' ] = $default_posts_per_page;
		$args[ 'paged' ] = $paged;
		$loop = new WP_Query( $args );

		foreach ( $loop->posts as $product_id ) {
			if ( $this->is_restricted_by_id( $product_id ) ) {
				$post__not_in[] = $product_id;
			}
		}
		
		// bulk rules query
		if ( !empty( $this->get_restricted_categories() ) || !empty( $this->get_restricted_tags() ) ) {
    		$tax_query = array(
    			array(
    				'taxonomy' => 'product_cat',
    				'field' => 'slug',
    				'terms' => $this->get_restricted_categories(),
    				'operator'=> 'NOT IN'
    			),
    			array(
    				'taxonomy' => 'product_tag',
    				'field' => 'slug',
    				'terms' => $this->get_restricted_tags(),
    				'operator'=> 'NOT IN'
    			),
        	);
    		$query->set( 'tax_query', $tax_query );
    	}
				
		foreach ( $loop->posts as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( $this->is_restricted_by_bulk( $product ) ) {
				$post__not_in[] = $product_id;
			}
		}

		$query->set( 'post__not_in', $post__not_in );
		
		if ( class_exists( "SitePress" ) && isset( $query->query_vars[ 'p' ] ) && in_array( $query->query_vars[ 'p' ], $post__not_in ) ) {
			$query->set( 'p', '0' );
		}

		add_action( 'pre_get_posts', array( $this, 'product_by_country_pre_get_posts' ) );
	}
	
	/*
	* Query args modify for Visual Composser products of restricted
	*
	* @since  1.0
	* @Competibility with Visual Composser plugin
	*/
	function vc_product_restricted_query( $query_args ) {

		if ( is_admin() ) {
			return $query_args;
		}
		
		// when post_type is not product or not a category/shop page return 
		if ( isset( $query_args[ 'post_type' ] ) && 'product' != $query_args[ 'post_type' ] ) {
			return $query_args;
		}
		
		// shop, category, search = visible, single page visible
		if ( 'show_catalog_visibility' == get_option('product_visibility') ) {
			return $query_args;
		}
		
		// shop, category, search = hidden, single page visible
		if ( 'hide_catalog_visibility' == get_option( 'product_visibility' ) && is_product() ) {
			return $query_args;
		}
		
		$post__in = array();
		if ( isset( $query_args[ 'post__in' ] ) ) {
			foreach ( $query_args[ 'post__in' ] as $product_id ) {
				if ( '1' != $this->is_restricted_by_id( $product_id ) ) {
					$post__in[] = $product_id;
				}
			}
		}
		
		if ( isset( $query_args[ 'post__in' ] ) ) {
			foreach ( $query_args['post__in'] as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( '1' != $this->is_restricted_by_bulk( $product ) ) {
					$post__in[] = $product_id;
				}
			}
		}
		if ( !empty( $post__in ) ) {
			$query_args[ 'post__in' ] = $post__in;
		}
		
		return $query_args;
	}
	
	/*
	* removed restricted related products by the product id for single product page
	*
	* @since  1.0
	*/
	function restricted_related_products( $related_posts, $product_id, $arg ) {

		if ( 'hide_completely' != get_option( 'product_visibility' ) ) {
			return $related_posts;
		}

		foreach ( $related_posts as $key => $id ) {
			if ( $this->is_restricted_by_id( $id ) ) {
				unset( $related_posts[ $key ] );
			}
		}

		return $related_posts;
	}
	
	/*
	* removed restricted upsell products for single product page
	*
	* @since  1.0
	*/
	function restricted_upsell_products( $upsell_ids,  $instance ){
		
		if ( 'hide_completely' != get_option( 'product_visibility' ) ) {
			return $upsell_ids;
		}
		
		foreach ( $upsell_ids as $key => $id ) {
			if ( $this->is_restricted_by_id( $id ) ) {
				unset( $upsell_ids[ $key ] );
			}
		}
		
		return $upsell_ids;
	}
	
	/**
	 * redirect 404 error page.
	 *
	 * @since  1.0
	 */
    function redirect_404_to_homepage( $page_dir ) {
	    if ( is_404() && '1' == get_option( 'wpcbr_redirect_404_page' ) ) {
			$redirect_page = get_option( 'wpcbr_choose_the_page_to_redirect', wc_get_page_id( 'shop' ) );
			$page_dir = esc_url( get_permalink( $redirect_page ) );
			wp_safe_redirect( $page_dir );
			exit;
		}
	}
	
	/**
	 * cart item message update by filter
	 *
	 * @since  1.0
	 */
	function cart_item_removed_massage( $message, $product ) {
		
		if ( $this->is_restricted( $product ) || apply_filters( 'cbr_is_restricted', false, $product ) ) {
			$message = sprintf( esc_html( '%s has been removed from your cart because it can no longer be purchased. Please contact us if you need assistance.', 'woocommerce' ), $product->get_name() );
			$message = apply_filters( 'cbr_cart_message', $message, $product );
			$message = apply_filters( 'cbr_cart_item_removed_message', $message, $product );
		}
		return $message;
	}
	
	/*
	* update cart and checkout items list
	*
	* @since  1.0
	*/
	function update_cart_and_checkout_items() {	
		global $woocommerce;
		$woocommerce->cart->get_cart_from_session();
	}
	
	/*
	* get cart message from settings
	* 
	* @since  1.0
	*/
	public function add_cbr_cart_message( $message, $product ) {
		
		$message = esc_html( '{Product_Name} has been removed from your cart since it is not available for purchase to your Country.', 'country-base-restrictions-pro-addon' );
		
		$cart_message = !empty( get_option( 'wpcbr_cart_message' ) ) ? get_option( 'wpcbr_cart_message' ) : $message;
		
		$product_name_with_link = '<a style="margin:0;" href="' . get_permalink( $product->get_id() ) . '">' . $product->get_name() . '</a>';
		
		$cart_message = str_replace( '{Product_name_with_link}', $product_name_with_link, $cart_message );
		
		$message = str_replace(	'{Product_Name}', $product->get_name(), $cart_message);

		return $message;
	}
	
	/*
	* get array of restricted category in specific country 
	* 
	* @since  1.0
	* return Array
	*/
	function get_restricted_categories() {
		
		if ( !empty( $this->restricted_categories ) ) {
			return $this->restricted_categories;
		}
		
		$customercountry = $this->get_country();
		$bulk_data = get_option( "cbr_bulk_restrictions", array() );
		
		$categories = array();
		
		foreach ( (array)$bulk_data as $data ) {
			
			if ( !empty( $data[ 'field_exclusivity' ] ) && 'enabled' != $data[ 'field_exclusivity' ] ) {
				continue;
			}
			
			if ( 'specific' == $data[ 'geographic_availability' ] && !empty( $data[ 'selected_countries' ] ) && in_array( $customercountry, $data[ 'selected_countries' ] ) ) {
				continue;
			}
	
			if ( 'excluded' == $data[ 'geographic_availability' ] && !empty( $data[ 'selected_countries' ] ) && !in_array( $customercountry, $data[ 'selected_countries' ] ) ) {
				continue;
			}
			
			if ( 'categories' == $data[ 'restriction_by' ] && isset( $data[ 'selected_category' ] ) ) {
				foreach ( $data[ 'selected_category' ] as $key => $category ) {
					$categories[] = $category;	
				}
			}
		}
		
		return $this->restricted_categories = $categories;

	}
	
	/*
	* get array of restricted tag in specific country 
	* 
	* @since  1.0
	* return Array
	*/
	function get_restricted_tags() {
		
		if ( !empty( $this->restricted_tags ) ) {
			return $this->restricted_tags;
		}
		
		$customercountry = $this->get_country();
		$bulk_data = get_option( "cbr_bulk_restrictions", array() );
		$tags = array();
		
		foreach ( (array)$bulk_data as $data ) {
			
			if ( !empty( $data[ 'field_exclusivity' ] ) && 'enabled' != $data[ 'field_exclusivity' ] ) {
				continue;
			}
			
			if ( 'specific' == $data[ 'geographic_availability' ] && !empty( $data[ 'selected_countries' ] ) && in_array( $customercountry, $data[ 'selected_countries' ] ) ) {
				continue;
			}

			if ( 'excluded' == $data[ 'geographic_availability' ] && !empty( $data[ 'selected_countries' ] ) && !in_array( $customercountry, $data[ 'selected_countries' ] ) ) {
				continue;
			}

			if ( 'tags' == $data[ 'restriction_by' ] && isset( $data[ 'selected_tag' ] ) ) {
				foreach ( (array)$data[ 'selected_tag' ] as $key => $tag ) {
					$tags[] = $tag;	
				}
			}
		}

		return $this->restricted_tags = $tags;

	}
	
	/*
	* get array of restricted attribute in specific country 
	* 
	* @since  1.0
	* return Array
	*/
	function get_restricted_attributes() {
		
		if ( !empty( $this->restricted_attributes ) ) {
			return $this->restricted_attributes;
		}
		
		$customercountry = $this->get_country();
		$bulk_data = get_option( "cbr_bulk_restrictions", array() );
		
		$attributes = array();
		
		foreach ( (array)$bulk_data as $data ) {
			
			if ( !empty( $data[ 'field_exclusivity' ] ) && 'enabled' != $data[ 'field_exclusivity' ] ) {
				continue;
			}
			
			if ( 'specific' == $data[ 'geographic_availability' ] && !empty( $data[ 'selected_countries' ] ) && in_array( $customercountry, $data[ 'selected_countries' ] ) ) {
				continue;
			}
	
			if ( 'excluded' == $data[ 'geographic_availability' ] && !empty( $data[ 'selected_countries' ] ) && !in_array( $customercountry, $data[ 'selected_countries' ] ) ) {
				continue;
			}
 
			if ( 'attributes' == $data[ 'restriction_by' ] && isset( $data[ 'selected_attribute' ] ) ) {
				foreach ( (array)$data[ 'selected_attribute' ] as $key => $attribute ) {
					$attributes[] = $attribute;	
				}
			}
		}
		return $this->restricted_attributes = $attributes;

	}
	
	/*
	* get array of restricted shipping class in specific country 
	* 
	* @since  1.0
	* return Array
	*/
	function get_restricted_shipping_class() {
		
		if ( !empty( $this->restricted_shipping_class ) ) return $this->restricted_shipping_class;
		
		$customercountry = $this->get_country();
		$bulk_data = get_option( "cbr_bulk_restrictions", array() );
		
		$shipping_class = array();
		
		foreach ( (array)$bulk_data as $data ) {
			
			if ( !empty( $data[ 'field_exclusivity' ] ) && 'enabled' != $data[ 'field_exclusivity' ] ) {
				continue;
			}
			
			if ( 'specific' == $data[ 'geographic_availability' ] && !empty( $data[ 'selected_countries' ] ) && in_array( $customercountry, $data[ 'selected_countries' ] ) ) {
				continue;
			}
	
			if ( 'excluded' == $data[ 'geographic_availability' ] && !empty( $data[ 'selected_countries' ] ) && !in_array( $customercountry, $data[ 'selected_countries' ] ) ) {
				continue;
			}
 
			if ( 'shipping-class' == $data[ 'restriction_by' ] && isset( $data[ 'selected_shipping_class' ] ) ){
				$shipping_class = $data[ 'selected_shipping_class' ];	
			}
		}
		return $this->restricted_shipping_class = $shipping_class;

	}
	
	/*
	* return is_restricted_globally
	* 
	* @since  1.0
	* return bool
	*/
	function is_restricted_globally() {
		
		if ( !empty( $this->restricted_global ) ) return $this->restricted_global;
		
		$customercountry = $this->get_country();
		$bulk_data = get_option( "cbr_bulk_restrictions", array() );
		
		$global = false;
		
		foreach ( (array)$bulk_data as $data ) {
			
			if ( !empty( $data[ 'field_exclusivity' ] ) && 'enabled' != $data[ 'field_exclusivity' ] ) {
				continue;
			}
			
			if ( 'specific' == $data[ 'geographic_availability' ] && !empty( $data[ 'selected_countries' ] ) && in_array( $customercountry, $data[ 'selected_countries' ] ) ) {
				continue;
			}
	
			if ( 'excluded' == $data[ 'geographic_availability' ] && !empty( $data[ 'selected_countries' ] ) && !in_array( $customercountry, $data[ 'selected_countries' ] ) ) {
				continue;
			}
 
			if ( 'global' == $data[ 'restriction_by' ] ) {
				$global = true;	
			}
		}
		return $this->restricted_global = $global;

	}
	
	/*
	* is product is restricted by category
	* 
	* @since  1.0
	* return bool true/false
	*/
	function is_restricted_by_bulk( $product ) {
		
		if ( !$product ) { 
			return false;
		}

		//Global
		$restricted_globally = $this->is_restricted_globally();
		if ( true == $restricted_globally ) {
			return true;
		}
		
		//category
		$cats = get_the_terms( $product->get_id(), 'product_cat' );
		$array_cat = $product->get_category_ids();

		$restricted_categories = $this->get_restricted_categories();
		if ( is_array( $cats ) ) {
			foreach ( $cats as $cat ) {	
				if ( isset( $cat->slug ) && in_array( $cat->slug, $restricted_categories ) ) {
					return true;
				}
			}
		}
		
		//tag
		$tags = get_the_terms( $product->get_id(), 'product_tag' );
		$restricted_tags = $this->get_restricted_tags();

		if ( is_array( $tags ) ) {
			foreach( $tags as $tag ) {
				if ( isset( $tag->slug ) && in_array( $tag->slug, $restricted_tags ) ) {
					return true;
				}
			}
		}
		
		//attributes
		if ( 'variable' != $product->get_type() || 'hide_completely' == get_option( 'product_visibility' ) ) {
						
			//attributes
			$restricted_attributes = $this->get_restricted_attributes();
			
			$attributes = $product->get_attributes();
			
		
			$attr_ids = array();
			
			foreach ( $attributes as $key => $attribute ) {
			
				if ( is_object( $attribute ) ) {
					$attribute_data = $attribute->get_data();
					foreach ( $attribute_data[ 'options' ] as $key => $id ) {
						$attr_ids[] = $id; 
					}
				} else {
				    $tag = get_term_by( 'slug', $attribute, $key );
				    $attr_ids[] = isset( $tag->term_id ) ? $tag->term_id : ''; 
				}
			}			
			if ( is_array( $attr_ids ) ) {
				foreach ( $attr_ids as $id ) {
					if ( in_array( $id, $restricted_attributes ) ) {
						return true;
					}
				}
			}
		}				
			
		if ( 'variation' == $product->get_type() ){			
			$restricted_attributes = $this->get_restricted_attributes();
			$attributes = $product->get_attributes();
			foreach( $attributes as $key => $value){
				$tag = get_term_by( 'slug', $value, $key );
				if ( isset( $tag->term_id ) && in_array( $tag->term_id, $restricted_attributes ) ) {
					return true;
				}
			}			
			
		}
		
		//shiping class
		$restricted_shipping_class = $this->get_restricted_shipping_class();
		
		// Get the Simple/Variation Product instance Object
		$product = wc_get_product($product->get_id()); 
		$term_id = $product ? $product->get_shipping_class_id() : '';

		if ( in_array( $term_id, $restricted_shipping_class ) ) {
			return true;
		}
		
		return false;	
	}
	
	/*
	* used to print restriction message in product page
	*
	* @since  1.0
	* return true/false 
	*/
	function is_restricted_by_cbr_pro( $bool, $product ) {

		if ( $this->is_restricted_by_bulk( $product ) ) {
			return true;
		}
		
		return $bool;
		
	}
	
	/*
	* Hide product price on product page
	* restrict by product
	*
	* @since  1.0
	* return $price
	*/
	function woocommerce_hide_product_price( $price, $product ) {
		
		if ( '1' != get_option( 'wpcbr_hide_product_price' ) || is_admin() ) { 
			return $price;
		}
		
		if ( $this->is_restricted( $product ) || apply_filters( 'cbr_is_restricted', false, $product ) ) {
			if ( !$product->is_purchasable() ) {
				return '';
			}
		}

		return $price;

	}
	
	/*
	* callback message for Restricted category page
	*
	* @since  1.0
	*/
	function restricted_category_message() {
		if ( 'show_catalog_visibility' == get_option( 'product_visibility' ) ) {
			return;
		}
		if ( is_product_category() ) {
			global $wp_query;
			$default_message = get_option( 'cbr_cat_default_message' );
			$cat = $wp_query->get_queried_object();
			$restricted_categories = $this->get_restricted_categories();
			if ( in_array( $cat->slug, $restricted_categories ) ) {
				if ( !empty( $default_message ) ) {
					echo "<p>" . stripslashes( $default_message ) . "</p>";
				} else {
					echo "<p>" . esc_html( 'Sorry, products from this category are not available to purchase in your country.', 'country-base-restrictions-pro-addon' ) . "</p>";
				}
			}
		}
		if ( is_product_tag() ) {
			global $wp_query;
			$default_message = get_option( 'cbr_cat_default_message' );
			$tag = $wp_query->get_queried_object();
			$restricted_tags = $this->get_restricted_tags();
			if ( in_array( $tag->slug, $restricted_tags ) ) {
				if ( !empty( $default_message ) ) {
					echo "<p>" . stripslashes( $default_message ) . "</p>";
				}else{
					echo "<p>" . esc_html( 'Sorry, products from this tag are not available to purchase in your country.', 'country-base-restrictions-pro-addon' ) . "</p>";
				}
			}
		}
	}
	
	/**
	 * WooCommerce Remove Payment Gateway for a Restricted/Specific Country
	 *
	 * @since  1.0
	 * @compatible    WooCommerce 4.0
	 */
	function cbr_restrict_payment_gateway_by_country( $available_gateways ) {
		
		if ( is_admin() ) {
			return $available_gateways;
		}
		
		if ( !isset( wc()->customer ) ) {
			return $available_gateways;
		}
		
		$debug_mode = get_option( "wpcbr_debug_mode", '0' );
		$shippingCountry = wc()->customer->get_shipping_country();
		$billingCountry = wc()->customer->get_billing_country();		
		$customercountry = $this->get_country();		
			
		if ( !$shippingCountry || 'default' == $shippingCountry ) {
			$shippingCountry = $customercountry;
		}
		
		if ( !$billingCountry || 'default' == $billingCountry ) {
			$billingCountry = $customercountry;
		}
				
		$ck_restricted_data = get_option( 'cbr_checkout_restrictions', array() );
		
		foreach ( $ck_restricted_data as $val ) {
			if ( 'enabled' == $val[ 'ck_rule_toggle' ] ) {
				$customer_country = 'billing' == $val[ 'customer_country' ] ? $billingCountry : $shippingCountry;
				$restriction_rule = isset( $val[ 'restriction_rule' ] ) ? $val[ 'restriction_rule' ] : '';
				$payment_methods = isset( $val[ 'payment_methods' ] ) ? $val[ 'payment_methods' ] : array();
				foreach ( $payment_methods as $key ) {
					if ( $restriction_rule == 'include' ) {
						if ( isset( $available_gateways[ $key ] ) && isset( $val[ 'restrict_countries' ] ) && !in_array( $customer_country, (array)$val[ 'restrict_countries' ] ) ) { unset( $available_gateways[ $key ] ); }
					} else {
						if ( isset( $available_gateways[ $key ] ) && isset( $val[ 'restrict_countries' ] ) && in_array( $customer_country, (array)$val[ 'restrict_countries' ] ) ) { unset( $available_gateways[ $key ] ); }	
					}
				}
			}
		}
		
		return $available_gateways;
	}
	
}
