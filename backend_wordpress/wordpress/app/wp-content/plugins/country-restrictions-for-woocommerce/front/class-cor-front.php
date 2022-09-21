<?php
		/**
		 * Class Start
		 *
		 * @package Woo-cor
		 */

if ( ! class_exists( 'Cor_Front' ) ) {
	/**
	 * Class Start
	 */
	class Cor_Front extends Cor_main_Class {
		/**
		 * Constructor Start
		 */
		public $cor_cntry_rest;
		
		public function __construct() {

			$this->cor_cntry_rest = $this->addf_cor_restriction_rule();
					// hide prices 
			add_action( 'woocommerce_get_price_html', array( $this, 'tyches_hide_prices_on_all_pages' ), 20, 2 );
					// add script
			add_action( 'wp_enqueue_scripts', array( $this, 'cor_Front_Scripts' ) );

					// hide from shop ,cat,tag
			add_action( 'woocommerce_product_query', array($this, 'cor_country_restriction_hide_show_products_callback') ); 
					// hide from single product page 
			add_action( 'template_redirect', array($this, 'cor_hide_from_single_product_page_callback') ); 
					//  hide from related products
			add_filter( 'woocommerce_product_is_visible' , array( $this, 'cor_hide_related_check_visibility_rules' ), 10, 2 );
					// for payment methods
			add_filter('woocommerce_available_payment_gateways', array($this, 'cor_payment_method_settings'), 10, 1);


					// removing add to cart button
			add_action( 'woocommerce_after_shop_loop_item', array($this, 'remove_add_to_cart_button_shop'), 1 );
					// hide from single product page
			add_action( 'woocommerce_single_product_summary', array( $this, 'cor_hide_add_to_cart_single_product_page' ), 10, 3 );
			add_filter( 'woocommerce_countries_allowed_countries', array($this, 'cor_product_disable_countries'), 10, 1 );
		}

		public function cor_product_disable_countries( $countries) {
			global $cart;
			foreach ( $this->cor_cntry_rest as $rule ) {

				$cor_enable_product_hide_option = get_post_meta( intval( $rule->ID ), 'cor_enable_bluk', true );
				$cor_selected_countries         = get_post_meta( intval( $rule->ID ), 'cor_countries', true );
				$targeted_ids                   = $this->addf_cor_check_rule_for_product_merge( $rule->ID );
				$cor_enable_product_hide_enable = get_post_meta( intval( $rule->ID ), 'cor_vis_opt', true );

				// Flag
				$found = false;
				if ('any' == $cor_enable_product_hide_enable) {
					if ( WC()->cart ) {         
						// Loop through cart items
						foreach ( WC()->cart->get_cart() as $cart_item ) {
							if ( array_intersect( $targeted_ids, array( $cart_item['product_id'], $cart_item['variation_id'] ) ) ) {
								$found = true;
								// break;
							}
						}
					}
					// True
					if ( $found ) {
						foreach ($cor_selected_countries as $country_key ) {
							if (!empty($country_key)) {
								unset( $countries[ $country_key ] );
							}
						}
					}
				} elseif ('all' == $cor_enable_product_hide_enable) {
					if ( WC()->cart ) {         
							// Loop through cart items
						foreach ( WC()->cart->get_cart() as $cart_item ) {
							if ( array_intersect( $targeted_ids, array( $cart_item['product_id'], $cart_item['variation_id'] ) ) ) {
								$found = true;
									// break;
							}
						}
					}
						// True
					if ( $found ) {
						$countries = array(); 
						foreach ($cor_selected_countries as $country_key ) {
							if (!empty($country_key)) {
								$countries[ $country_key ] = $this->cor_get_woo_countires_name($country_key);
							}
						}
					}
				}
			}
			return $countries;

		}
		public function cor_get_woo_countires_name( $country_key) {

			$cor_all_countries = WC()->countries->get_shipping_countries();
			$cor_country_name  = in_array($country_key, array_keys($cor_all_countries), true)?$cor_all_countries[$country_key] :'';
			return $cor_country_name;

		} 
		/**
		 * Start script
		 */
		public function cor_Front_Scripts() {
			$in_footer = true;
			// Upload Font-Awesome 4.
			wp_enqueue_style( 'Font_Awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css', false, '1.0' );
			// Enqueue Front CSS.
			wp_enqueue_style( 'cor_front_css', plugins_url( '../assets/css/cor_front.css', __FILE__ ), false, '1.0' );
			
			// Enqueue Front JS.
			wp_enqueue_script( 'cor-front', plugins_url( '../assets/js/cor-front.js', __FILE__ ), true, '1.0.0', $in_footer );
			wp_localize_script( 'cor-front', 'addf_cor_my_ajax_object',
				array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script( 'jquery' );

		}
		// start for rule based restrictions
		public function addf_cor_restriction_rule() {
			$args = array(
				'post_type'        => 'country_restriction',
				'post_status'      => 'publish',
				'numberposts'      => 	'-1',
				'orderby'          => 'menu_order',
				'order'            => 'ASC',
				'suppress_filters' => true,
			);
			return get_posts( $args );
		}


		//  for single product page
		public function cor_hide_from_single_product_page_callback() {

			if ( !is_singular( 'product' )) {
				return;
			}
			$location                    = WC_Geolocation::geolocate_ip();
			$cor_visitor_current_country = $location['country'];
			if ( $this->cor_cntry_rest) {
				foreach ( $this->cor_cntry_rest as $rule ) {
					$cor_enable_product_hide_option = get_post_meta( intval( $rule->ID ), 'cor_enable_bluk', true );
					$cor_enable_product_hide_enable = get_post_meta( intval( $rule->ID ), 'cor_vis_opt', true );
					$cor_selected_countries         = get_post_meta( intval( $rule->ID ), 'cor_countries', true );
					$message_show_on_restricted     = get_post_meta( intval( $rule->ID ), 'cor_r_message', true );
					$cor_enable_product_hide_list   = get_post_meta( intval( $rule->ID ), 'cor_product', true );
					$cor_db_p_cat                   = get_post_meta( intval( $rule->ID ), 'cor_categories', true );
					$cor_db_p_tag                   = get_post_meta( intval( $rule->ID ), 'cor_tags', true );
					if ( empty($cor_db_p_cat) && empty($cor_db_p_tag) && empty($cor_enable_product_hide_list)) {
						return;
					}
					 
					if (( in_array($cor_visitor_current_country, (array) $cor_selected_countries) )||( empty( $cor_visitor_current_country ) )) {
						if (( '1' == $cor_enable_product_hide_option )) {

							if ('any' == $cor_enable_product_hide_enable) {
								if (!in_array(get_the_ID() , (array) $this->addf_cor_check_rule_for_product_merge( $rule->ID ))) {
									return;
								} else {
									$addf_cor_div_page = get_post_meta( intval( $rule->ID ), 'addf_cor_redirect_method', true );
									if ( '2' == $addf_cor_div_page ) {
										wp_safe_redirect( get_permalink( get_post_meta( intval( $rule->ID ), 'addf_cor_redirect_page', true ) ) );
										exit;
									}
									if ( '3' == $addf_cor_div_page ) {
										$cor_redirect_ext_link = esc_url(get_post_meta( intval( $rule->ID ), 'addf_cor_redirect_link', true ));
										wp_redirect($cor_redirect_ext_link);
										exit;
									}
									if ( '1' == $addf_cor_div_page ) {
										if ( is_singular( 'product' ) ) {
											if ( is_single( get_the_ID() ) ) {
												remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
											}
											get_header();
											?>
											<div id="content">
												<p class="woocommerce-info"><?php echo esc_html__('Product Not Found', 'Woo-cor' ); ?></p>
												<?php
												if (!$message_show_on_restricted) {
													?>
													<p  class="woocommerce-info"><?php echo esc_html__('This product may not available in your country.', 'Woo-cor' ); ?></p>
													<?php
												} else {
													echo '<p class="woocommerce-info">';
													echo esc_html__( $message_show_on_restricted , 'Woo-cor' );
													echo  '</p>';
												}
												?>
											</div>
											<?php 
											exit;
										}
									}
								}
							} elseif ('all' == $cor_enable_product_hide_enable) {
								if ( in_array( get_the_ID( ) , (array) $this->addf_cor_check_rule_for_product_merge( $rule->ID ))) {
									return;
								} else {
									$addf_cor_div_page = get_post_meta( intval( $rule->ID ), 'addf_cor_redirect_method', true );
									if ( '2' == $addf_cor_div_page ) {
										wp_safe_redirect( get_permalink( get_post_meta( intval( $rule->ID ), 'addf_cor_redirect_page', true ) ) );
										exit;
									}
									if ( '3' == $addf_cor_div_page ) {
										$cor_redirect_ext_link = esc_url( get_post_meta( intval( $rule->ID ), 'addf_cor_redirect_link', true ));
										wp_redirect($cor_redirect_ext_link);
										exit;
									}
									if ( '1' == $addf_cor_div_page ) {
										if ( is_singular( 'product' ) ) {
											if ( is_single( get_the_ID() ) ) {
												remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
											}
											get_header();
											?>
											<div id="content">
												<p class="woocommerce-info"><?php echo esc_html__('Product Not Found', 'Woo-cor' ); ?></p>
												<?php
												if ( '' != $message_show_on_restricted ) {
													?>
													<p class="woocommerce-info">
														<?php
														echo esc_html__( $message_show_on_restricted , 'Woo-cor' );
														?>
													</p>
													<?php
												} else {
													?>
													<p class="woocommerce-info"><?php echo esc_html__('This product may not available in your country.', 'Woo-cor' ); ?></p>
													<?php
												}
												?>
											</div>
											<?php 
											exit;
										}
									}
								}
							}
						}
					} elseif (( !in_array($cor_visitor_current_country, (array) $cor_selected_countries) )||( ! is_array( $cor_selected_countries ) )) {
						$op_for_unselected_countries = get_option('cor_unselected_couteries_shop_method');
						if ( ( '2' === $op_for_unselected_countries ) ) { 
							$cor_product_level_args     = array(
								'numberposts' => -1,    
								'post_status' => array('publish'),
								'post_type' => array('product'), 
								'fields' => 'ids',
								'meta_query'  => array(
									array(
										'key' => 'enbility_product_level_exclude_visibility_rules',
										'value' => 'yes'
									)
								)
							);
							$cor_product_level_my_query = get_posts($cor_product_level_args);
							if ( is_array($cor_product_level_my_query) ) {
								if ( !in_array( get_the_ID() , $cor_product_level_my_query)) {
									if ( is_singular( 'product' ) ) {
										if ( is_single( get_the_ID() ) ) {
											remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
											get_header();
											$cor_redirect_unselected_c_message = get_option('cor_redirect_unselected_c_message');
											if ( '' == $cor_redirect_unselected_c_message) {
												$cor_redirect_unselected_c_message = 'Product are restricted in your country';
											}
											?>
											<div id="content">
												<p class="woocommerce-info"><?php echo esc_html__(  $cor_redirect_unselected_c_message , 'Woo-cor' ); ?></p>
											</div>
											<?php 
											exit;
										}
									}
								}
							}
						} // else show all products ...single_products...;
					}
				}
			} else {
				$op_for_unselected_countries = get_option('cor_unselected_couteries_shop_method');
				if ( ( '2' === $op_for_unselected_countries ) ) {
					// show nothing  ...single_products...";
					$cor_product_level_args     = array(
						'numberposts' => -1,    
						'post_status' => array('publish'),
						'post_type' => array('product'), 
						'fields' => 'ids',
						'meta_query'  => array(
							array(
								'key' => 'enbility_product_level_exclude_visibility_rules',
								'value' => 'yes'
							)
						)
					);
					$cor_product_level_my_query = get_posts($cor_product_level_args);
					if ( is_array($cor_product_level_my_query) ) {
						if ( ! in_array( get_the_ID() , $cor_product_level_my_query)) {
							if ( is_singular( 'product' ) ) {
								if ( is_single( get_the_ID() ) ) {
									remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
									get_header();
									$cor_redirect_unselected_c_message = get_option('cor_redirect_unselected_c_message');
									if ( '' == $cor_redirect_unselected_c_message) {
										$cor_redirect_unselected_c_message = 'Product are restricted in your country';
									}
									?>
									<div id="content">
										<p class="woocommerce-info"><?php echo esc_html__(  $cor_redirect_unselected_c_message , 'Woo-cor' ); ?></p>
									</div>
									<?php 
									exit;
								}
							}
						}
					}
				} // else show all products ...single_products...;
			}
		}
		//  remove products from shop page
		public function cor_country_restriction_hide_show_products_callback( $q ) {
			$location                    = WC_Geolocation::geolocate_ip();
			$cor_visitor_current_country = $location['country'];
			if ($this->cor_cntry_rest) {
				foreach ( $this->cor_cntry_rest as $rule ) {
					$cor_enable_product_hide_option = get_post_meta( intval( $rule->ID ), 'cor_enable_bluk', true );
					$cor_enable_product_hide_enable = get_post_meta( intval( $rule->ID ), 'cor_vis_opt', true );
					$cor_selected_countries         = get_post_meta( intval( $rule->ID ), 'cor_countries', true );
					$cor_enable_product_hide_list   = get_post_meta( intval( $rule->ID ), 'cor_product', true );
					$cor_db_p_cat                   = get_post_meta( intval( $rule->ID ), 'cor_categories', true );
					$cor_db_p_tag                   = get_post_meta( intval( $rule->ID ), 'cor_tags', true );
					$location                       = WC_Geolocation::geolocate_ip();
					$cor_visitor_current_country    = $location['country'];
					//  for categories 
					if ( empty($cor_db_p_cat)&& empty($cor_db_p_tag) && empty($cor_enable_product_hide_list) ) {
						return $visible;
					}
					//  for categories
					if ('1' == $cor_enable_product_hide_option) { 
						if (( in_array($cor_visitor_current_country, (array) $cor_selected_countries) )&&( is_array($cor_selected_countries) )) {
							if ('any' == $cor_enable_product_hide_enable) {
								$cor_enable_product_hide_list_any = (array) $this->addf_cor_check_rule_for_product_merge( $rule->ID );
								$post__not_in                     = (array) $q->get('post__not_in');
								$q->set('post__not_in', array_merge($post__not_in, $cor_enable_product_hide_list_any));
							} elseif ('all' == $cor_enable_product_hide_enable) {
								$cor_db_p_cat                 = is_array( $cor_db_p_cat ) ? $cor_db_p_cat : array();
								$cor_enable_product_hide_list = (array) $this->addf_cor_check_rule_for_product_merge( $rule->ID );
								$post__in                     = (array) $q->get('post__in');
								$q->set('post__in', array_merge($post__in, $cor_enable_product_hide_list));
							}
						} elseif (( !in_array($cor_visitor_current_country, (array) $cor_selected_countries) )||( ! is_array($cor_selected_countries) )) {
							$op_for_unselected_countries = get_option('cor_unselected_couteries_shop_method');
							if ( ( '2' === $op_for_unselected_countries ) ) {
								$cor_product_level_args     = array(
									'numberposts' => -1,    
									'post_status' => array('publish'),
									'post_type' => array('product'), 
									'fields' => 'ids',
									'meta_query'  => array(
										array(
											'key' => 'enbility_product_level_exclude_visibility_rules',
											'value' => 'yes'
										)
									)
								);
								$cor_product_level_my_query = get_posts($cor_product_level_args);
								if ( is_array($cor_product_level_my_query) && ( ! empty($cor_product_level_my_query) ) ) {
									$q->set('post__in', $cor_product_level_my_query);
								} else {
									$empty_var = ' ';
									$q->set('post__in', (array) $empty_var);
									remove_action( 'woocommerce_no_products_found', 'wc_no_products_found' );
									add_action( 'woocommerce_no_products_found', array($this,'cor_hide_from_unselected_countries'), 35);
								}
							}  // show all products ...listing_pages_products...
						}
						// break;
					} else { 
						$op_for_unselected_countries = get_option('cor_unselected_couteries_shop_method');
						if ( ( '2' === $op_for_unselected_countries ) ) {
							if (( ! in_array($cor_visitor_current_country, (array) $cor_selected_countries) )&&( is_array($cor_selected_countries) )) {

								$cor_product_level_args     = array(
									'numberposts' => -1,    
									'post_status' => array('publish'),
									'post_type' => array('product'), 
									'fields' => 'ids',
									'meta_query'  => array(
										array(
											'key' => 'enbility_product_level_exclude_visibility_rules',
											'value' => 'yes'
										)
									)
								);
								$cor_product_level_my_query = get_posts($cor_product_level_args);
								if ( is_array($cor_product_level_my_query) && ( ! empty($cor_product_level_my_query) ) ) {
									$q->set('post__in', $cor_product_level_my_query);
								} else {
									$empty_var = ' ';
									$q->set('post__in', (array) $empty_var);
									remove_action( 'woocommerce_no_products_found', 'wc_no_products_found' );
									add_action( 'woocommerce_no_products_found', array($this,'cor_hide_from_unselected_countries'), 35);
								}
							} 
						}  // show all products ...listing_pages_products...
					}
				}
			} else {
				$op_for_unselected_countries = get_option('cor_unselected_couteries_shop_method');
				if ( ( '2' === $op_for_unselected_countries ) ) {
					$cor_product_level_args     = array(
						'numberposts' => -1,    
						'post_status' => array('publish'),
						'post_type' => array('product'), 
						'fields' => 'ids',
						'meta_query'  => array(
							array(
								'key' => 'enbility_product_level_exclude_visibility_rules',
								'value' => 'yes'
							)
						)
					);
					$cor_product_level_my_query = get_posts($cor_product_level_args);
					if ( is_array($cor_product_level_my_query) && ( ! empty($cor_product_level_my_query) ) ) {
						$q->set('post__in', $cor_product_level_my_query);
					} else {
						$empty_var = ' ';
						$q->set('post__in', (array) $empty_var);
						remove_action( 'woocommerce_no_products_found', 'wc_no_products_found' );
						add_action( 'woocommerce_no_products_found', array($this,'cor_hide_from_unselected_countries'), 35);
					}
				}
			}
		}
		public function cor_hide_from_unselected_countries() {
			if (is_shop() || is_product_category() || is_product_tag() ) {
				$cor_redirect_unselected_c_message = get_option('cor_redirect_unselected_c_message');
				if ( '' == $cor_redirect_unselected_c_message) {
					$cor_redirect_unselected_c_message = 'Product are restricted in your country';
				}
				?>
				<p class="woocommerce-info"><?php echo esc_html__(  $cor_redirect_unselected_c_message , 'Woo-cor' ); ?></p> 
				<?php 
			}
		}
						// hide prices
		public function tyches_hide_prices_on_all_pages( $price, $product ) {
			$location                                 = WC_Geolocation::geolocate_ip();
			$cor_visitor_current_country              =  $location['country'];
			$cor_enbility_product_level_product_level = get_post_meta( get_the_ID() , 'enbility_product_level_disability' , true );
			$cor_restricted_countries_product_level   = get_post_meta( get_the_ID() , 'cor_restricted_countries' , true );
			$cor_restricted_price_product_level       = get_post_meta( get_the_ID() , 'cor_product_price' , true );
			$cor_restricted_price_text_product_level  = get_post_meta( get_the_ID() , 'cor_product_price_text_product_level' , true );
			if ( 'variation' == $product->get_type() ) {
				$addf_cor_variation_id            =  $product->get_id();
				$addf_cor_variation_id_enability  = get_post_meta( $addf_cor_variation_id , 'cor_restricted_countries_pl_cb' , true );
				$addf_cor_variation_id_countries  = get_post_meta( $addf_cor_variation_id , 'cor_restricted_countries_pl' , true );
				$addf_cor_variation_id_hide_price = get_post_meta( $addf_cor_variation_id , 'cor_restricted_countries_pl_cb_hide_price' , true );
				if ( 'yes' === $addf_cor_variation_id_enability) {
					if ( in_array( $cor_visitor_current_country , (array) $addf_cor_variation_id_countries ) ) {
						if ( 'yes' === $addf_cor_variation_id_hide_price ) {
							$cor_restricted_countries_pl_cb_hide_price_msg = get_post_meta( $addf_cor_variation_id , 'cor_restricted_countries_pl_cb_hide_price_msg' , true );
							if ( '' != $cor_restricted_countries_pl_cb_hide_price_msg) {
								return esc_html__(  $cor_restricted_countries_pl_cb_hide_price_msg , 'Woo-cor' );
							}
							return ' ';
						}
					}
				}
			}
			if ( 'yes' === $cor_enbility_product_level_product_level) {
				if ('yes' === $cor_restricted_price_product_level) {
					if (( in_array($cor_visitor_current_country, (array) $cor_restricted_countries_product_level) )&&( is_array( $cor_restricted_countries_product_level ) )) {
						if ($cor_restricted_price_text_product_level) {
							return $cor_restricted_price_text_product_level;
						}
						return ' ';
					}
				}
			} else {
				
				foreach ( $this->cor_cntry_rest as $rule ) {

					$cor_enable_product_hide_option = get_post_meta( intval( $rule->ID ), 'cor_enable_bluk', true );
					$cor_selected_countries         = get_post_meta( intval( $rule->ID ), 'cor_countries', true );
					$cor_hide_prices_pages          = get_post_meta( intval( $rule->ID ), 'cor_pages', true );
					$cor_message_on_prices          = get_post_meta( intval( $rule->ID ), 'cor_price_text', true );
					$cor_confirm_hide_price         = get_post_meta(  intval( $rule->ID ), 'cor_price_opt' , true);
					$location 						= WC_Geolocation::geolocate_ip();
					$cor_visitor_current_country    = $location['country']; 

					if ('2' === $cor_enable_product_hide_option) {

						if (( in_array($cor_visitor_current_country, (array) $cor_selected_countries) )&&( is_array($cor_selected_countries) )) {

							if ( 'hide_pr' == $cor_confirm_hide_price || ( 'hide_pr' == get_option('cor_hide_show_product_price_setting') &&  'show_pr' != $cor_confirm_hide_price ) ) {

								if (in_array(get_the_ID() , (array) $this->addf_cor_check_rule_for_product_merge( $rule->ID ))) {
									$to_print_cor = $cor_message_on_prices ? $cor_message_on_prices : get_option('cor_hide_price_text');

									return $to_print_cor;
								}
							} elseif ( 'show_pr' == $cor_confirm_hide_price  ) {

								if ( !in_array(get_the_ID() , (array) $this->addf_cor_check_rule_for_product_merge( $rule->ID )) && 'hide_pr' == get_option('cor_hide_show_product_price_setting')) {
									$to_print_cor =  get_option('cor_hide_price_text');

									return $to_print_cor;									
								}

							}
						}
					}
				}
			}
			return  $price;
		} 
		// Hide add to cart.
		public function remove_add_to_cart_button_shop() {
			$location                                        = WC_Geolocation::geolocate_ip();
			$cor_visitor_current_country                     = $location['country'];
			$cor_enbility_product_level_product_level        = get_post_meta( get_the_ID() , 'enbility_product_level_disability' , true );
			$cor_restricted_countries_product_level          = get_post_meta( get_the_ID() , 'cor_restricted_countries' , true );
			$cor_restricted_add_to_cart_product_level        = get_post_meta( get_the_ID() , 'cor_product_add' , true );
			$cor_restricted_add_to_cart_product_level_option = get_post_meta( get_the_ID() , 'cor_hide_r_add_cart_product_level' , true );
			if ( 'yes' === $cor_enbility_product_level_product_level) {
				if ( ( 'yes' === $cor_restricted_add_to_cart_product_level )) {
					if (( in_array($cor_visitor_current_country, (array) $cor_restricted_countries_product_level) )&&( is_array( $cor_restricted_countries_product_level ) )) {
						remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
						if ( '3' == $cor_restricted_add_to_cart_product_level_option) {
							echo esc_html__(get_post_meta(get_the_ID(), 'cor_add_text' , true), 'woo-cor');
						} elseif ( '2' == $cor_restricted_add_to_cart_product_level_option) {
							$redirect_link_product_side = get_post_meta(get_the_ID(), 'cor_replace_add_to_cart_custom_btn_product_level' , true);
							?>
							<a href="<?php echo esc_url($redirect_link_product_side); ?>">
								<button><?php echo esc_html__(get_post_meta( get_the_ID() , 'cor_product_price_cart_btn_product_level' , true ), 'woo-cor'); ?></button>
							</a>
							<?php
						} 
					}
				}
				return;
				
			} else {
				add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
				foreach ( $this->cor_cntry_rest as $rule ) {
					$cor_enable_product_hide_option = get_post_meta( intval( $rule->ID ), 'cor_enable_bluk', true );
					$cor_selected_countries         = get_post_meta( intval( $rule->ID ), 'cor_countries', true );
					$cor_add_cart_opt               = get_post_meta( intval( $rule->ID ), 'cor_add_cart_opt', true );
					$hide_cart_on_pages             = get_post_meta( intval( $rule->ID ), 'cor_add_pages', true);
					$hide_add_to_cart_opt 			= get_post_meta( intval( $rule->ID ), 'cor_hide_r_add_cart', true ); 
					$text_after_add_to_cart         = get_post_meta( intval( $rule->ID ), 'cor_add_text', true );
					$btn_text_after_add_to_cart     = get_post_meta( intval( $rule->ID ), 'cor_text_for_replace_cart_btn', true );
					$btn_text_after_add_to_cartlink = get_post_meta( intval( $rule->ID ), 'cor_replace_add_to_cart_custom_btn', true );

					$cor_hide_btn_custom_message_a_t_c = get_option('cor_hide_btn_custom_message_a_t_c');
					$cor_custom_button_message_a_t_c   = get_option('cor_custom_button_message_a_t_c');
					$custom_btn_link_message           =  get_option('cor_custom_button_link_a_t_c');
					$location                          = WC_Geolocation::geolocate_ip();
					$cor_visitor_current_country       = $location['country'];

					if ('2' === $cor_enable_product_hide_option) {

						if ( $cor_visitor_current_country && is_array( $cor_selected_countries ) && in_array($cor_visitor_current_country, (array) $cor_selected_countries) ) {
						 
							if ( 'hide_cart_btn' == $cor_add_cart_opt || ( 'hide_cart_btn' == get_option('cor_hide_show_add_to_cart_button_setting') &&  'show_cart_btn' != $cor_add_cart_opt ) ) { 

								if (in_array(get_the_ID() , (array) $this->addf_cor_check_rule_for_product_merge( $rule->ID ))) {  
									remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
									if ( '1' == $hide_add_to_cart_opt) {
										echo ' ';
										return;										
									} elseif ( '2' == $hide_add_to_cart_opt) { 
										?>
										<a type="button" href="<?php echo esc_url($btn_text_after_add_to_cartlink) ; ?>">
											<button>
												<?php echo esc_html__($btn_text_after_add_to_cart , 'Woo-cor' ); ?>
											</button>
										</a>
										<?php
										return;
									} elseif ( '3' == $hide_add_to_cart_opt) {
										echo '<div class="afcortext">' . esc_html__($text_after_add_to_cart , 'Woo-cor' ) . '</div>'; 
										return;
									}
								}
							} elseif ( 'show_cart_btn' == $cor_add_cart_opt  ) {

								if ( !in_array(get_the_ID() , (array) $this->addf_cor_check_rule_for_product_merge( $rule->ID )) && 'hide_cart_btn' == get_option('cor_hide_show_add_to_cart_button_setting')) {		
									remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' ); 
									if ( 'hide_btn' == get_option('cor_button_replace_options_a_t_c') ) {
										return;										
									} elseif ( 'replace_btn' == get_option('cor_button_replace_options_a_t_c') ) {
										 
										?>
										<a type="button" href="<?php echo esc_url($custom_btn_link_message) ; ?>">
											<button>
												<?php echo esc_html__($cor_custom_button_message_a_t_c , 'Woo-cor' ); ?>
											</button>
										</a>
										<?php
										return;
									} elseif ( 'msg_btn' == get_option('cor_button_replace_options_a_t_c') ) {
										echo '<div class="afcortext">' . esc_html__($cor_hide_btn_custom_message_a_t_c , 'Woo-cor' ) . '</div>'; 
										return;
									}					
								}

							}
						}
					}
				}
			}
		}

		// hide Add to Cart from singal product 
		public function cor_hide_add_to_cart_single_product_page() {
			global $product;
			$location                    = WC_Geolocation::geolocate_ip();
			$cor_visitor_current_country = $location['country']; 
			?>
			<input type="hidden" class="addf_cor_current_country" value="<?php echo esc_html__($cor_visitor_current_country , 'woo-cor' ); ?>">
			<script type="text/javascript">
				jQuery(function($){
								// On selected variation event
								$('form.variations_form').on('show_variation', function(event, data){
									var variation_id = data.variation_id;
									jQuery.ajax({
											url: addf_cor_my_ajax_object.ajax_url, // in backend you should pass the ajax url using this variable
											type: 'POST',
											data: { 
												action : 'addf_cor_hide_add_to_cart_price_ajax_call_back', 
												variation_id: variation_id,
												current_country: $(".addf_cor_current_country").val()
											},
											success: function(data){
												if('yes' == data['success']){
													$('.single_add_to_cart_button').hide('10');
													$('.addf_cor_add_custm_btn_vl').hide();
													$('.woocommerce-variation-add-to-cart ').append(data['addf_cor_cart_replace']);
													$('.qty').hide('10');
												}else if('no' == data['success']){
													$('.addf_cor_add_custm_btn_vl').hide();
													$('.single_add_to_cart_button').show();
													$('.qty').show();
												}
											}
										});
								});
								// On unselected (or not selected) variation event
								$('form.variations_form').on('hide_variation', function(){
									$('.addf_cor_add_custm_btn_vl').hide();
									$('.single_add_to_cart_button').show();
									$('.qty').show();
								});
							});
						</script>
						<?php

						$cor_restricted_countries_product_level   = get_post_meta( get_the_ID() , 'cor_restricted_countries' , true );
						$cor_enbility_product_level_product_level = get_post_meta( get_the_ID() , 'enbility_product_level_disability' , true );
						$cor_restricted_add_to_cart_product_level = get_post_meta( get_the_ID() , 'cor_product_add' , true );
						if ( 'yes' === $cor_enbility_product_level_product_level) {
							if ('yes' === $cor_restricted_add_to_cart_product_level) {
								if (( in_array($cor_visitor_current_country, (array) $cor_restricted_countries_product_level) )&&( is_array( $cor_restricted_countries_product_level ) )) {
									global $product;
									if ( 'variable' === $product->get_type() ) {
										remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
										add_action( 'woocommerce_single_variation', array( $this, 'addf_cor__cart_custom_button_replacement' ), 20 );
									} else {
										remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
										add_action( 'woocommerce_simple_add_to_cart', array( $this, 'addf_cor__cart_custom_button_replacement' ), 30 );
									}
								}
							}
							// return;
						} else {
							foreach ( $this->cor_cntry_rest as $rule ) {
								$cor_enable_product_hide_option = get_post_meta( intval( $rule->ID ), 'cor_enable_bluk', true );
								$cor_add_cart_opt               = get_post_meta( intval( $rule->ID ), 'cor_add_cart_opt', true );
								$cor_selected_countries         = get_post_meta( intval( $rule->ID ), 'cor_countries', true );
								$hide_cart_on_pages             = get_post_meta( intval( $rule->ID ), 'cor_add_pages', true);
								$location                       = WC_Geolocation::geolocate_ip();
								$cor_visitor_current_country    = $location['country']; 
								if ( $cor_visitor_current_country && is_array( $cor_selected_countries ) &&  in_array($cor_visitor_current_country, (array) $cor_selected_countries) ) {
									if ('2' == $cor_enable_product_hide_option) {
										if (  'hide_cart_btn' == $cor_add_cart_opt || 'hide_cart_btn' == get_option('cor_hide_show_add_to_cart_button_setting') ) { 
											if (!in_array(get_the_ID() , (array) $this->addf_cor_check_rule_for_product_merge( $rule->ID )) || in_array(get_the_ID() , (array) $this->addf_cor_check_rule_for_product_merge( $rule->ID ) ) ) {

												global $product;
												if ( 'variable' === $product->get_type() ) {
													remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
													add_action( 'woocommerce_single_variation', array( $this, 'addf_cor__cart_custom_button_replacement' ), 20 );
												} else {
													remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
													add_action( 'woocommerce_simple_add_to_cart', array( $this, 'addf_cor__cart_custom_button_replacement' ), 30 );
												}
											}
										}
									}
								}
							}
						}
		}
		public function addf_cor__cart_custom_button_replacement() {
			$location                                        = WC_Geolocation::geolocate_ip();
			$cor_visitor_current_country                     = $location['country']; 
			$cor_restricted_countries_product_level          = get_post_meta( get_the_ID() , 'cor_restricted_countries' , true );
			$cor_enbility_product_level_product_level        = get_post_meta( get_the_ID() , 'enbility_product_level_disability' , true );
			$cor_restricted_add_to_cart_product_level        = get_post_meta( get_the_ID() , 'cor_product_add' , true );
			$cor_restricted_add_to_cart_product_level_option = get_post_meta( get_the_ID() , 'cor_hide_r_add_cart_product_level' , true );
			if ( 'yes' === $cor_enbility_product_level_product_level) {
				if ('yes' === $cor_restricted_add_to_cart_product_level) {
					if (( in_array($cor_visitor_current_country, (array) $cor_restricted_countries_product_level) )&&( is_array( $cor_restricted_countries_product_level ) )) {
						if ( '3' == $cor_restricted_add_to_cart_product_level_option) {
							echo esc_html__(get_post_meta(get_the_ID(), 'cor_add_text' , true), 'woo-cor');
						} elseif ( '2' == $cor_restricted_add_to_cart_product_level_option) {
							$redirect_link_product_side = get_post_meta(get_the_ID(), 'cor_replace_add_to_cart_custom_btn_product_level' , true);
							?>
										<a href="<?php echo esc_url($redirect_link_product_side); ?>">
											<button><?php echo esc_html__(get_post_meta( get_the_ID() , 'cor_product_price_cart_btn_product_level' , true ), 'woo-cor'); ?></button>
										</a>
										<?php
						} 
						return;
					}
				}
			} else {
				foreach ( $this->cor_cntry_rest as $rule ) {
					$cor_enable_product_hide_option    = get_post_meta( intval( $rule->ID ), 'cor_enable_bluk', true );
					$cor_add_cart_opt                  = get_post_meta( intval( $rule->ID ), 'cor_add_cart_opt', true );
					$hide_add_to_cart_opt              = get_post_meta( intval( $rule->ID ), 'cor_hide_r_add_cart', true );
					$text_after_add_to_cart            = get_post_meta( intval( $rule->ID ), 'cor_add_text', true );
					$btn_text_after_add_to_cart        = get_post_meta( intval( $rule->ID ), 'cor_text_for_replace_cart_btn', true );
					$cor_selected_countries            = get_post_meta( intval( $rule->ID ), 'cor_countries', true );
					$btn_text_after_add_to_cartlink    = get_post_meta( intval( $rule->ID ), 'cor_replace_add_to_cart_custom_btn', true );
					$cor_hide_btn_custom_message_a_t_c = get_option('cor_hide_btn_custom_message_a_t_c');
					$cor_custom_button_message_a_t_c   = get_option('cor_custom_button_message_a_t_c');
					$custom_btn_link_message           =  get_option('cor_custom_button_link_a_t_c');
					if ('2' == $cor_enable_product_hide_option) {

						if ( $cor_visitor_current_country && is_array( $cor_selected_countries ) && in_array($cor_visitor_current_country, (array) $cor_selected_countries) ) {
							 
							if ( 'hide_cart_btn' == $cor_add_cart_opt || ( 'hide_cart_btn' == get_option('cor_hide_show_add_to_cart_button_setting') &&  'show_cart_btn' != $cor_add_cart_opt ) ) {

								if (in_array(get_the_ID() , (array) $this->addf_cor_check_rule_for_product_merge( $rule->ID ))) { 
									remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
									if ( '1' == $hide_add_to_cart_opt) {
										echo apply_filters('lb_multicountry_hide_add_to_cart_btn', '');
										return;
									} elseif ( '2' == $hide_add_to_cart_opt) {
										?>
													<a type="button" class="button" href="<?php echo esc_url($btn_text_after_add_to_cartlink) ; ?>">
											<?php echo esc_html__($btn_text_after_add_to_cart , 'Woo-cor' ); ?>
													</a>
													<?php
													return;
									} elseif ( '3' == $hide_add_to_cart_opt) {
										echo esc_html__($text_after_add_to_cart  , 'Woo-cor' ); 
										return;
									}
								}
							} elseif ( 'show_cart_btn' == $cor_add_cart_opt  ) {

								if ( !in_array(get_the_ID() , (array) $this->addf_cor_check_rule_for_product_merge( $rule->ID )) && 'hide_cart_btn' == get_option('cor_hide_show_add_to_cart_button_setting')) {		
									remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' ); 
									if ( 'hide_btn' == get_option('cor_button_replace_options_a_t_c') ) {
										return;										
									} elseif ( 'replace_btn' == get_option('cor_button_replace_options_a_t_c') ) {
										 
										?>
													<a type="button" href="<?php echo esc_url($custom_btn_link_message) ; ?>">
														<button>
												<?php echo esc_html__($cor_custom_button_message_a_t_c , 'Woo-cor' ); ?>
														</button>
													</a>
													<?php
													return;
									} elseif ( 'msg_btn' == get_option('cor_button_replace_options_a_t_c') ) {
										echo '<div class="afcortext">' . esc_html__($cor_hide_btn_custom_message_a_t_c , 'Woo-cor' ) . '</div>'; 
										return;
									}					
								}

							}
						}
					}
				}
			}
		}
										// for payment methods Without Rules
		public function cor_payment_method_settings( $available_payment_gateways ) {
				$location                    = WC_Geolocation::geolocate_ip();
				$cor_visitor_current_country = $location['country'];

				$cor_countries_payment_method_bacs   = get_option('cor_countries_payment_method_bacs');
				$cor_payment_enablility_bacs         = get_option('cor_payment_enablility_bacs');
				$cor_countries_payment_method_cheque = get_option('cor_countries_payment_method_cheque');
				$cor_payment_enablility_cheque       = get_option('cor_payment_enablility_cheque');
				$cor_countries_payment_method_cod    = get_option('cor_countries_payment_method_cod');
				$cor_payment_enablility_cod          = get_option('cor_payment_enablility_cod');
				$cor_countries_payment_method_paypal = get_option('cor_countries_payment_method_paypal');
				$cor_payment_enablility_paypal       = get_option('cor_payment_enablility_paypal');

			if ( '' != $cor_visitor_current_country) {
				foreach ( $available_payment_gateways as $key => $payment_gateway ) {
								
					if (empty( $payment_gateway->title ) ) {
							continue;
					}
								
					$cor_countries_payment_method_id = 'cor_countries_payment_method_' . $payment_gateway->id;
					$cor_payment_enablility_id       = 'cor_payment_enablility_' . $payment_gateway->id;

					$cor_countries_payment_method = get_option($cor_countries_payment_method_id);

					$cor_payment_enablility = get_option($cor_payment_enablility_id);
					if (empty($cor_countries_payment_method)) {
								continue;
					}

					if ( is_array($cor_countries_payment_method)&&( in_array($cor_visitor_current_country , (array) $cor_countries_payment_method) ) ) {

						if ( '2' == $cor_payment_enablility) {
										unset($available_payment_gateways[$payment_gateway->id]);
								 
						}
					} elseif (is_array($cor_countries_payment_method) && ( !in_array($cor_visitor_current_country , (array) $cor_countries_payment_method) ) ) {

						if ( '1' == $cor_payment_enablility) {
							if (isset($available_payment_gateways[$payment_gateway->id])) {
								unset($available_payment_gateways[$payment_gateway->id]);
							}
										
						}
					}
								
				}				 
			}		  
				return $available_payment_gateways;
		}

				//  filter from related products
		public function cor_hide_related_check_visibility_rules( $visible, $product_id ) {
			$location                    = WC_Geolocation::geolocate_ip();
			$cor_visitor_current_country = $location['country'];  
			if ($this->cor_cntry_rest) { 
				foreach ( $this->cor_cntry_rest as $rule ) {
					// general
					$cor_enable_product_hide_option = get_post_meta( intval( $rule->ID ), 'cor_enable_bluk', true );
					$cor_inc_exc_opt                = get_post_meta( intval( $rule->ID ), 'cor_inc_exc_opt', true );
					$cor_enable_product_hide_enable = get_post_meta( intval( $rule->ID ), 'cor_vis_opt', true );
					$cor_selected_countries         = get_post_meta( intval( $rule->ID ), 'cor_countries', true );
					$cor_enable_product_hide_list   = get_post_meta( intval( $rule->ID ), 'cor_product', true );
					$cor_db_p_cat                   = get_post_meta( intval( $rule->ID ), 'cor_categories', true );
					$cor_db_p_tag                   = get_post_meta( intval( $rule->ID ), 'cor_tags', true );
					if ('1' == $cor_enable_product_hide_option) { 
						if ( empty($cor_db_p_cat)&& empty($cor_db_p_tag) && empty($cor_enable_product_hide_list) ) {
							if (( in_array($cor_visitor_current_country, (array) $cor_selected_countries) )&&( is_array( $cor_selected_countries) )) {
								return $visible;
							}
						}
					}
					if (( in_array($cor_visitor_current_country, (array) $cor_selected_countries) )&&( is_array( $cor_selected_countries ) )) {
						if ('1' == $cor_enable_product_hide_option) {
							if ('any' == $cor_enable_product_hide_enable) {

								if (( in_array($product_id, (array) $this->addf_cor_check_rule_for_product_merge( $rule->ID )) )) {
									continue;
								}
								return $visible;
							} elseif ('all' == $cor_enable_product_hide_enable) {
								if (( in_array($product_id, (array) $this->addf_cor_check_rule_for_product_merge( $rule->ID )) )) {
									return $visible;
								} else {
									return false;
								}
								// else return visible
							}
						} 
					} elseif (( !in_array($cor_visitor_current_country, (array) $cor_selected_countries) )||( ! is_array( $cor_selected_countries ) )) {
						$op_for_unselected_countries = get_option('cor_unselected_couteries_shop_method');
						if ( ( '2' === $op_for_unselected_countries ) ) {
							$cor_product_level_args     = array(
								'numberposts' => -1,    
								'post_status' => array('publish'),
								'post_type' => array('product'), 
								'fields' => 'ids',
								'meta_query'  => array(
									array(
										'key' => 'enbility_product_level_exclude_visibility_rules',
										'value' => 'yes'
									)
								)
							);
							$cor_product_level_my_query = get_posts($cor_product_level_args);
							if ( is_array($cor_product_level_my_query)&&( in_array($product_id , $cor_product_level_my_query) ) ) {
								return $visible;
							}
							return false;
							// else show nothing  ...related_products...
						}
						return $visible;
					}}
			} else {
						$op_for_unselected_countries = get_option('cor_unselected_couteries_shop_method');
				if ( ( '2' === $op_for_unselected_countries ) ) {
					$cor_product_level_args     = array(
					'numberposts' => -1,    
					'post_status' => array('publish'),
					'post_type' => array('product'), 
					'fields' => 'ids',
					'meta_query'  => array(
					array(
					'key' => 'enbility_product_level_exclude_visibility_rules',
					'value' => 'yes'
					)
					)
					);
					$cor_product_level_my_query = get_posts($cor_product_level_args);
					if ( is_array($cor_product_level_my_query)&&( in_array($product_id , $cor_product_level_my_query) ) ) {
						return $visible;
					}
					return false;
				}
						return $visible;
			}
				return $visible;
		}


		// merging function for product lists
		public function addf_cor_check_rule_for_product_merge( $rule_id ) {
			$cor_enable_product_hide_list   = get_post_meta( intval( $rule_id ), 'cor_product', true );
			$cor_db_p_cat                   = get_post_meta( intval( $rule_id ), 'cor_categories', true );
			$cor_db_p_tag                   = get_post_meta( intval( $rule_id ), 'cor_tags', true );
			$cor_enable_product_hide_option = get_post_meta( intval( $rule_id ), 'cor_enable_bluk', true );
			$cor_enable_product_hide_enable = get_post_meta( intval( $rule_id ), 'cor_vis_opt', true );

			if (is_array($cor_db_p_cat)) {
				$cor_tax_query_p_cats              = array( 
					'numberposts' => -1,	
					'post_status' => array('publish'),
					'post_type' => array('product'), 
					'fields' => 'ids'
				);
				$cor_tax_query_p_cats['tax_query'] = array( 
					array( 
						'taxonomy' => 'product_cat', 
						'field' => 'id', 
						'terms' => $cor_db_p_cat, 
						'operator' => 'IN', 
					)
				);
				$cor_products_ids_from_cats        =  get_posts($cor_tax_query_p_cats);
			} else {
				$cor_products_ids_from_cats =  array('');
			}
			if ( is_array($cor_db_p_tag)) {
				$cor_tax_query_p_tags              = array( 
					'numberposts' => -1, 
					'post_status' => array('publish'), 
					'post_type' => array('product'), 
					'fields' => 'ids'
				);
				$cor_tax_query_p_tags['tax_query'] = array(
					array(
						'taxonomy' => 'product_tag',
						'field' => 'id',
						'terms' => $cor_db_p_tag,
						'operator' => 'IN',
					));
				$cor_products_ids_from_tags        =  get_posts($cor_tax_query_p_tags);
			} else {
				$cor_products_ids_from_tags =  array('');
			}
			$cor_enable_product_hide_list =  array_merge( (array) $cor_enable_product_hide_list, (array) $cor_products_ids_from_cats );
			$cor_enable_product_hide_list =  array_merge( (array) $cor_enable_product_hide_list, (array) $cor_products_ids_from_tags );
			if ( '1' === $cor_enable_product_hide_option) {
				if ('any' == $cor_enable_product_hide_enable) {
					$cor_product_level_args     = array(
						'numberposts' => -1,    
						'post_status' => array('publish'),
						'post_type' => array('product'), 
						'fields' => 'ids',
						'meta_query'  => array(
							array(
								'key' => 'enbility_product_level_exclude_visibility_rules',
								'value' => 'yes'
							)
						)
					);
					$cor_product_level_my_query = get_posts($cor_product_level_args);
					if ( is_array($cor_product_level_my_query) ) {
						$cor_enable_product_hide_list = \array_diff( (array) $cor_enable_product_hide_list, (array) $cor_product_level_my_query);
					}
				} elseif ('all' == $cor_enable_product_hide_enable) {
					$cor_product_level_args     = array(
						'numberposts' => -1,    
						'post_status' => array('publish'),
						'post_type' => array('product'), 
						'fields' => 'ids',
						'meta_query'  => array(
							array(
								'key' => 'enbility_product_level_exclude_visibility_rules',
								'value' => 'yes'
							)
						)
					);
					$cor_product_level_my_query = get_posts($cor_product_level_args);
					if ( is_array( $cor_product_level_my_query) ) {
						$cor_enable_product_hide_list =  array_merge( (array) $cor_enable_product_hide_list, (array) $cor_product_level_my_query );
					}
				}
			}
			return $cor_enable_product_hide_list;
		}

	}
			new Cor_Front();
}
