<?php
/**
 * Class Start
 *
 * @package Woo-cor
 */
if ( ! class_exists( 'Cor_Admin' ) ) {
		/**
		 * Class Start
		 */
	class Cor_Admin extends Cor_Main_Class {
		/**
		 * Start Constructor
		 */
		public function __construct() {
			// Enqueue Admin CSS JS.
			add_action( 'admin_enqueue_scripts', array( $this, 'cor_Admin_enqueue_scripts' ) );

			// add menus.
			add_action( 'admin_menu', array( $this, 'cor_register_woocommerce_menu' ) );
			
			add_action( 'admin_init', array( $this, 'addf_cor_setting_files' ) );

			add_filter( 'woocommerce_product_data_tabs', array( $this, 'create_cor_tab' ), 30);

			add_action( 'woocommerce_product_data_panels', array( $this, 'display_cor_fields' ) );

			add_action( 'woocommerce_process_product_meta', array( $this, 'cor_save_custom_product_fields' ) );
			// Settings Fields.

			add_action( 'init', array( $this, 'cor_addify_cor_post_type' ) );

			add_action( 'add_meta_boxes', array( $this, 'addify_cor_meta_box' ) );

			add_action( 'save_post', array( $this, 'cor_save_metaData' ), 10, 2 );

			// ajax call for prod selection
			add_action('wp_ajax_cor_getproductsearch', array( $this, 'cor_ex_get_product_ajax_callback' ) );

			// Add Fields into Variable Product's Variations
			add_action('woocommerce_variation_options', array($this, 'addf_cor_add_fields_in_variation_products'), 10, 3);
			//Save Variations custom fields 
			add_action('woocommerce_save_product_variation', array($this, 'Afadv_save_custom_field_variations'), 10, 2);

			//  hide cart  variation level
			add_action('wp_ajax_addf_cor_hide_add_to_cart_price_ajax_call_back', array( $this, 'addf_cor_hide_add_to_cart_price_ajax_call_back' ) );

		}
		
		/**
		 * Class Script Start
		 */
		public function cor_Admin_enqueue_scripts() {
			wp_enqueue_script( 'cor_admin', plugins_url( '../assets/js/cor_admin.js', __FILE__ ), false, '1.0', $in_footer = false );
			wp_enqueue_style( 'cor_admins', plugins_url( '../assets/css/cor_admin.css', __FILE__ ), false, '1.0' );
			$afhp_data = array(
				'admin_url' => admin_url( 'admin-ajax.php' ),
			);
			wp_localize_script( 'cor_admin', ' cor_php_vars', $afhp_data );
			wp_enqueue_script( 'jquery' );
			// Enqueue Select2 JS CSS.
			wp_enqueue_style( 'select2', plugins_url( '../assets/css/select2.css', __FILE__ ), true, '1.0' );
			wp_enqueue_script( 'select2', plugins_url( '../assets/js/select2.js', __FILE__ ), false, '1.0', array( 'jquery' ) );
			// Enqueue WP_MEDIA.
			wp_enqueue_media();
		}

		/**
		 * Function Menu
		 */
		public function cor_register_woocommerce_menu() {
			add_menu_page( 
				esc_html__('Country Restriction', 'Woo-cor' ), 
				esc_html__('Country Restriction', 'Woo-cor' ), 
				'manage_options', 
				'edit.php?post_type=country_restriction',
				'',
				COR_URL . '/assets/img/grey.png',
				30
			);
			
			add_submenu_page( 
				'edit.php?post_type=country_restriction',
				esc_html__('Settings', 'Woo-cor' ), 
				esc_html__('Settings', 'Woo-cor' ), 
				'manage_options', 
				'addf_country_restriction_settings', 
				array($this, 'country_payment_method_callback')
			);
		} // end cor_Custom_Menu_Admin.
		/**
		 * Create tab
		 *
		 * @param For post id $tabs .
		 */
		public function create_cor_tab( $tabs ) {
			$tabs['cor'] = array(
				'label'    => esc_html__( 'Country Restriction', 'Woo-cor' ), // The name of your panel.
				'target'   => 'cor_panel', // Will be used to create an anchor link so needs to be unique.
				'class'    => array( 'cor_tab', 'show_if_simple', 'show_if_variable' ), // Class for your panel tab - helps hide/show depending on product type.
				'priority' => 80, // Where your panel will appear. By default, 70 is last item.
			);
			return $tabs;
		}
		/**
		 * Diplay fields
		 */
		public function display_cor_fields() { 
			global $post;
					$selections = get_post_meta( $post->ID, 'cor_restricted_countries', true );
			if ( empty( $selections ) || ! is_array( $selections ) ) {
				$selections = array();
			}
					$countries = WC()->countries->get_shipping_countries();
					asort( $countries );
			?>
			<div id='cor_panel' class='panel woocommerce_options_panel hidden'>
				<div class="options_group">
					<table class="cor-table">
						<tr class="cor-option-field ">
							<th class="width_input_text">
								<div class="option-head">
									<h4>
										<?php echo esc_html__( 'Exclude from product visibility rules', 'Woo-cor' ); ?>
									</h4>
								</div>
							</th>
							<td>
								<input type="checkbox" name="enbility_product_level_exclude_visibility_rules" id="enbility_product_level_exclude_visibility_rules" value="yes" <?php checked( get_post_meta(get_the_ID() , 'enbility_product_level_exclude_visibility_rules', true) , 'yes'); ?>>
								<p class="description"><?php echo esc_html__('Enable if you want to exclude this product from visibility rules' , 'woo-cor'); ?></p>
							</td>
						</tr>
						<tr class="cor-option-field ">
							<th class="width_input_text">
								<div class="option-head">
									<h4>
										<?php echo esc_html__( 'Enable / Disable', 'Woo-cor' ); ?>
									</h4>
								</div>
							</th>
							<td>
								<input type="checkbox" name="enbility_product_level_disability" id="enbility_product_level_disability" value="yes" <?php checked( get_post_meta(get_the_ID() , 'enbility_product_level_disability', true) , 'yes'); ?>>
								<p class="description"><?php echo esc_html__('Enable or disable product level settings' , 'woo-cor'); ?></p>
							</td>
						</tr>
						
						<tr class="cor-option-field ">
							<th class="width_input_text">
								<div class="option-head">
									<h4>
										<?php echo esc_html__( 'Select countries', 'Woo-cor' ); ?>
									</h4>
								</div>
							</th>
							<td>
								<select  id="cor_restricted_countries" multiple="multiple" name="cor_restricted_countries[]" style="width:100%;max-width: 350px;"
									data-placeholder="<?php echo esc_html__( 'Choose countries&hellip;', 'Woo-cor' ); ?>" title="<?php echo esc_html__( 'Country', 'Woo-cor' ); ?>"
									class="wc-enhanced-select" >
									<?php
									if ( ! empty( $countries ) ) {
										foreach ( $countries as $key => $val ) {
											echo '<option value="' . esc_attr( $key ) . '" ' . selected( in_array( $key, $selections, true ), true ) . '>' . esc_html__( $val , 'Woo-cor' ) . '</option>';
										}
									}
									?>
								</select>
								<br>
								<p class="description"><?php echo esc_html__('Choose countries for product restrictions' , 'woo-cor'); ?></p>
							</td>
						</tr>
						
						
						<tr class="cor-option-field ">
							<th class="width_input_text">
								<div class="option-head">
									<h4>
										<?php echo esc_html__( 'Restrict Add to Cart', 'Woo-cor' ); ?>
									</h4>
								</div>
							</th>
							<td>
								<input class="cor_input_class" type="checkbox" name="cor_product_add" id="cor_product_add" 
									value="yes"
									<?php
									checked( get_post_meta( get_the_ID(), 'cor_product_add', true ) , 'yes' );
									?>
									>
								<?php echo esc_html__( 'Restrict Add to Cart For This Product', 'Woo-cor' ); ?><br>
									<p class="description"><?php echo esc_html__('Check if you want to hide price of this product' , 'woo-cor'); ?></p>
							</td>
						</tr>
						<tr class="cor-option-field  addf_cor_restriction_option_product_side">
							<th class="width_input_text">
								<div class="option-head">
									<h4>
										<?php echo esc_html__( 'Select a method of hidding Add to Cart', 'Woo-cor' ); ?>
									</h4>
								</div>
							</th>
							<td>
								<select name="cor_hide_r_add_cart_product_level" id="addf_cor_restriction_option_product_side" class=" afrfq_input_class width_input" >
									<option value="1" <?php selected( esc_attr(get_post_meta( get_the_ID(), 'cor_hide_r_add_cart_product_level', true )), 1 ); ?>><?php echo esc_html__('Hide Add to Cart', 'Woo-cor' ); ?></option>
									<option value="2" <?php selected( esc_attr(get_post_meta( get_the_ID(), 'cor_hide_r_add_cart_product_level', true )), 2 ); ?>><?php echo esc_html__('Replace with a custom Button', 'Woo-cor' ); ?></option>
									<option value="3" <?php selected( esc_attr(get_post_meta( get_the_ID(), 'cor_hide_r_add_cart_product_level', true )), 3 ); ?>><?php echo esc_html__('Show a Message', 'Woo-cor' ); ?></option>
								</select>
								<br><br>
								<span class="description"><?php echo esc_html__( 'Select a option for Add to Cart button to hide', 'Woo-cor' ); ?></span><br>
							</td>
						</tr>
						<tr class=" cor-option-field cor_product_side_enter_text " >
							<th class="width_input_text">
								<div class="option-head">
									<h4>
										<?php echo esc_html__( 'Enter Text', 'Woo-cor' ); ?>
									</h4>
								</div>
							</th>
							<td>
								<input class="cor_input_class cor_restricted_countries width_input" type="text" name="cor_add_text" id="cor_add_text" 
									value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'cor_add_text', true ) , 'Woo-cor' ); ?>" >
									<br>
									<br>
								<p class="description "><?php echo esc_html__( 'Show message when Add to Cart is Restrict', 'Woo-cor' ); ?></p>
							</td>
						</tr>
						<tr class=" cor-option-field cor_product_side_enter_btn_text ">
							<th class="width_input_text">
								<div class="option-head">
									<h4>
										<?php echo esc_html__( 'Enter text for custom button', 'Woo-cor' ); ?>
									</h4>
								</div>
							</th>
							<td>
								<input type="text" name="cor_product_price_cart_btn_product_level" class="width_input price_hide_val afrfq_input_class" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'cor_product_price_cart_btn_product_level', true ) , 'Woo-cor' ); ?>" ><br>
								<br><p class="description "><?php echo esc_html__( 'Text for Custom button', 'Woo-cor' ); ?></p>
							</td>
						</tr>
						<tr class=" cor-option-field cor_product_side_enter_btn_text ">
							<th class="width_input_text">
								<div class="option-head">
									<h4>
										<?php echo esc_html__( 'Enter a link for Custom Button to redirect', 'Woo-cor' ); ?>
									</h4>
								</div>
							</th>
							<td>
								<input type="text" class=" price_hide_val afrfq_input_class width_input" name="cor_replace_add_to_cart_custom_btn_product_level" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'cor_replace_add_to_cart_custom_btn_product_level', true ) , 'Woo-cor' ); ?>">
								<br><br><p class="  description "><?php echo esc_html__( 'Show Custom button when Add to Cart is restrict. https:// not required' , 'Woo-cor' ); ?></p>
							</td>
						</tr>
					<tr class="cor-option-field ">
						<th class="width_input_text">
							<div class="option-head">
								<h4>
									<?php echo esc_html__( 'Restrict Price', 'Woo-cor' ); ?>
								</h4>
							</div>
						</th>
						<td>
							<input class="cor_input_class" type="checkbox" name="cor_product_price" id="cor_product_price" 
							value="yes"
							<?php
									checked( get_post_meta( get_the_ID(), 'cor_product_price', true ) , 'yes' );
							?>
									>
									<?php echo esc_html__( 'Restrict product price', 'Woo-cor' ); ?><br>
									<p class="description"><?php echo esc_html__('Check if you want to hide Add to Cart button for this product' , 'woo-cor'); ?></p>
						</td>
					</tr>
					<tr class="cor-option-field  cor_product_price">
						<th class="width_input_text">
							<div class="option-head">
								<h4>
									<?php echo esc_html__( 'Restrict price text', 'Woo-cor' ); ?>
								</h4>
							</div>
						</th>
						<td>
							<input type="text" name="cor_product_price_text_product_level" class="cor_product_price width_input" 
									value="<?php echo esc_html__(get_post_meta(get_the_ID() , 'cor_product_price_text_product_level' , true), 'woo-cor'); ?>">
							<br><br><p class="  description "><?php echo esc_html__( 'Text for hidden price' , 'Woo-cor' ); ?></p>
						</td>
					</tr>
				</table>
								<?php
								if ( empty( $countries ) ) {
									echo '<p><b>' . esc_html__( 'You need to setup shipping locations in WooCommerce settings ', 'Woo-cor' ) . " <a href='admin.php?page=wc-settings'> " . esc_html__( 'HERE', 'Woo-cor' ) . '</a> ' . esc_html__( 'before you can choose country restrictions', 'Woo-cor' ) . '</b></p>';
								}
								echo '</div>';
								echo '</div>';
		}

		/**
		 * Save fields data
		 *
		 * @param For post id $post_id .
		 */
		public function cor_save_custom_product_fields( $post_id ) {
			if ( isset( $_POST['cor_nonce_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cor_nonce_field'] ) ), 'cor_field' ) ) {
						echo '';
			}
			if ( isset( $_POST['cor_restriction_rule'] ) ) {
				$restriction = sanitize_text_field( wp_unslash( $_POST['cor_restriction_rule'] ) );
			}
			if ( ! is_array( $restriction ) ) {
				if ( ! empty( $restriction ) ) {
					update_post_meta( $post_id, 'cor_restriction_rule', $restriction );
				}
				$countries = array();
				if ( isset( $_POST['cor_restricted_countries'] ) ) {
					$countries = sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['cor_restricted_countries'] ), 'post' );
				}
				update_post_meta( $post_id, 'cor_restricted_countries', $countries );
			}
			if ( isset( $_POST['cor_product_add'] ) ) {
				$add_cart = sanitize_text_field( wp_unslash( $_POST['cor_product_add'] ) );
			}
				update_post_meta( $post_id, 'cor_product_add', $add_cart );
			if ( isset( $_POST['cor_product_price_text_product_level'] ) ) {
				update_post_meta( $post_id, 'cor_product_price_text_product_level', sanitize_text_field( wp_unslash( $_POST['cor_product_price_text_product_level'] ) ) );
			}
			if ( isset( $_POST['cor_restriction_product_level'] ) ) {
				update_post_meta( $post_id, 'cor_restriction_product_level', sanitize_text_field( wp_unslash( $_POST['cor_restriction_product_level'] ) ) );
			}
			if ( isset( $_POST['cor_add_text'] ) ) {
				update_post_meta( $post_id, 'cor_add_text', sanitize_text_field( wp_unslash( $_POST['cor_add_text'] ) ) );
			}
			if ( isset( $_POST['cor_hide_r_add_cart_product_level'] ) ) {
				update_post_meta( $post_id, 'cor_hide_r_add_cart_product_level', sanitize_text_field( wp_unslash( $_POST['cor_hide_r_add_cart_product_level'] ) ) );
			}
			if ( isset( $_POST['cor_product_price_cart_btn_product_level'] ) ) {
				update_post_meta( $post_id, 'cor_product_price_cart_btn_product_level', sanitize_text_field( wp_unslash( $_POST['cor_product_price_cart_btn_product_level'] ) ) );
			}
			if ( isset( $_POST['cor_replace_add_to_cart_custom_btn_product_level'] ) ) {
				update_post_meta( $post_id, 'cor_replace_add_to_cart_custom_btn_product_level', sanitize_text_field( wp_unslash( $_POST['cor_replace_add_to_cart_custom_btn_product_level'] ) ) );
			}
			if ( isset( $_POST['enbility_product_level_disability'] ) ) {
				update_post_meta( $post_id, 'enbility_product_level_disability', sanitize_text_field( wp_unslash( $_POST['enbility_product_level_disability'] ) ) );
			} else {
				update_post_meta( $post_id, 'enbility_product_level_disability', ' ' );
			}
			if ( isset( $_POST['enbility_product_level_exclude_visibility_rules'] ) ) {
				update_post_meta( $post_id, 'enbility_product_level_exclude_visibility_rules', sanitize_text_field( wp_unslash( $_POST['enbility_product_level_exclude_visibility_rules'] ) ) );
			} else {
				update_post_meta( $post_id, 'enbility_product_level_exclude_visibility_rules', ' ' );
			}
			if ( isset( $_POST['cor_product_price'] ) ) {
				$price_r = sanitize_text_field( wp_unslash( $_POST['cor_product_price'] ) );
			}
				update_post_meta( $post_id, 'cor_product_price', $price_r );
		}
		/**
		 * Register Custom Post
		 */
		public function cor_addify_cor_post_type() {
			$labels = array(
				'name'               => esc_html__( 'Country Restriction', 'Woo-cor' ),
				'singular_name'      => esc_html__( 'Country Restriction', 'Woo-cor' ),
				'menu_name'          => esc_html__( 'Country Restriction', 'Woo-cor' ),
				'parent_item_colon'  => esc_html__( 'Parent Item:', 'Woo-cor' ),
				'all_items'          => esc_html__( 'All Rule', 'Woo-cor' ),
				'view_item'          => esc_html__( 'View Messages', 'Woo-cor' ),
				'add_new_item'       => esc_html__( 'Add New Rule', 'Woo-cor' ),
				'add_new'            => esc_html__( 'Add New Rule', 'Woo-cor' ),
				'edit_item'          => esc_html__( 'Edit Rule', 'Woo-cor' ),
				'update_item'        => esc_html__( 'Update Rule', 'Woo-cor' ),
				'search_items'       => esc_html__( 'Search Rule', 'Woo-cor' ),
				'not_found'          => esc_html__( 'Not found', 'Woo-cor' ),
				'not_found_in_trash' => esc_html__( 'Not found in Trash', 'Woo-cor' ),
				'attributes' => esc_html__( 'Priority', 'Woo-cor' ),
			);
			$args   = array(
				'label'               => esc_html__( 'Country_Restriction', 'Woo-cor' ),
				'description'         => esc_html__( ' addify_Country_Restriction Description', 'Woo-cor' ),
				'labels'              => $labels,
				'supports'            => array( 'title','page-attributes' ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => false,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'capability_type'     => 'post',
			);
			register_post_type( 'country_restriction', $args );
		}
		
		public function addify_cor_meta_box() {
			add_meta_box(
				'country_main',
				esc_html__( 'Country Restriction Setting', 'Woo-cor' ),
				array( $this, 'koalaaps_country_form' ),
				'country_restriction'
			);
		}

		
		public function koalaaps_country_form() {

			?>
			<div class="cor_form">
				<table class="cor-table">
					<tr class="cor-option-field ">
						<th class="width_input_text">
							<div class="option-head">
								<h3>
									<?php echo esc_html__( 'Choose Countries', 'Woo-cor' ); ?>
								</h3>
							</div>
						</th>
						<td class="width_input">
							<?php
									$s_conts = get_post_meta( get_the_ID(), 'cor_countries', true );
									$s_conts = is_array( $s_conts ) ? $s_conts : array();
							?>
							<select name="cor_countries[]" id="cor_countries"  data-placeholder="<?php echo esc_html__('Choose Countries...', 'Woo-cor' ); ?>" class="chosen-select country_input_mb" multiple="multiple" tabindex="-1">;
								
								<?php
									$countr_obj = new WC_Countries();
									$countries  = $countr_obj->__get( 'countries' );
								foreach ( $countries as $key => $country ) {
									?>
										<option value="<?php echo esc_html( $key ); ?>" 
										<?php
										if ( in_array( $key, $s_conts, true ) ) {
											echo 'selected'; }
										?>
										>
										<?php echo esc_html__( $country , 'Woo-cor' ); ?>
										</option>
									<?php
								}
								?>
							</select><br>
							<p class="description"><?php echo esc_html__( 'Choose countries for restriction of products or hide price/Add to cart', 'Woo-cor' ); ?>
							<p class="description"><?php echo esc_html__( 'Geo-location must be enabled', 'Woo-cor' ); ?>
							</p >
						</td>
					</tr>
					<tr class="cor_enable_bluk">
						<th class="width_input_text">
							<div class="option-head">
								<h3>
									<?php echo esc_html__( 'Restriction Mode', 'Woo-cor' ); ?>
								</h3>
							</div>
						</th>
						<td>
							<select name="cor_enable_bluk" id="cor_enable_bluk" data-placeholder="<?php echo esc_html__('Select Option', 'Woo-cor' ); ?>" class="afrfq_input_class cor_enable_bluk">
								<option value="1" 
								<?php 
								if ('1' == ( get_post_meta( get_the_ID(), 'cor_enable_bluk', true ) )) {
									echo 'selected="selected"';
								}
								?>
									 id="cor_product_restriction"><?php echo esc_html__('Restrict Products', 'Woo-cor' ); ?></option>
								<option value="2" 
								<?php 
								if ('2' == ( get_post_meta( get_the_ID(), 'cor_enable_bluk', true ) )) {
									echo 'selected="selected"';
								}
								?>
									 id="price_and_add_to_cart"><?php echo esc_html__('Price & Add to Cart', 'Woo-cor' ); ?></option>
							</select>
							<p class="description"><?php echo esc_html__('Choose to restrict entire products or prices/add to cart button.', 'Woo-cor' ); ?></p>
						</td>
					</tr>

					<tr class="cor_hide_bluk_p">
						<th class="width_input_text">
							<div class="option-head">
								<h3>
									<?php echo esc_html__( 'Select Visibility', 'Woo-cor' ); ?>
								</h3>
							</div>

						</th>
						<td class="width_input">
							<p>
								<input class="cor_input_class product_hide_val" type="radio" name="cor_vis_opt" id="cor_vis_opt1" 
									value="all" 
									<?php
									if ( empty( get_post_meta( get_the_ID(), 'cor_vis_opt', true ) ) || 'all' === get_post_meta( get_the_ID(), 'cor_vis_opt', true ) ) {
										echo 'checked'; }
									?>
										>
								<?php echo esc_html__( 'Show products', 'Woo-cor' ); ?>
								&nbsp;&nbsp;
								<input class="cor_input_class product_hide_val" type="radio" name="cor_vis_opt" id="cor_vis_opt2" 
									value="any" 
									<?php
									if ( 'any' === get_post_meta( get_the_ID(), 'cor_vis_opt', true ) ) {
										echo 'checked'; }
									?>
								>
								<?php echo esc_html__( 'Hide products', 'Woo-cor' ); ?>
							</p>
							<p class="description"><?php echo esc_html__( 'Choose a action for selected countries', 'Woo-cor' ); ?></p>
						</td>
						<br>
					</tr>

					<tr class="">
						<th class="width_input_text">
							<div class="option-head">
								<h3>
									<?php echo esc_html__( 'Choose Products', 'Woo-cor' ); ?>
								</h3>
							</div>
						</th>
						<td class="width_input">
							<select name="cor_product[]" id="cor_product" data-placeholder="<?php echo esc_html__('Choose Products...', 'Woo-cor' ); ?>" class="js_multipage_select_product chosen-select select_input_width product_hide_val" multiple="multiple" tabindex="-1" >;								
								<?php
									$cor_specific_product = get_post_meta( get_the_ID(), 'cor_product', true );
								if ( ! empty( $cor_specific_product ) ) {
									foreach ( $cor_specific_product as $pro ) {
										$prod_post = get_post( $pro );
										?>
											<option value="<?php echo intval( $pro ); ?>" selected="selected"><?php echo esc_html__( $prod_post->post_title, 'Woo-cor' ); ?></option>
										<?php
									}
								}
								?>
							</select>
							<p class="description"><?php echo esc_html__( 'Choose products for selected action', 'Woo-cor' ); ?></p>
						</td>
					</tr>
					<tr class="">
						<th class="width_input_text">
							<div class="option-head">
								<h3>
									<?php echo esc_html__( 'Choose Categories', 'Woo-cor' ); ?>
								</h3>
							</div>
						</th>
						<td>
										<!-- for product cat -->
										<div class="cor_all_cats">
											<ul>
												<?php
													$cor_p_cat = get_post_meta( get_the_ID(), 'cor_categories', true );
													$cor_p_cat = is_array( $cor_p_cat ) ? $cor_p_cat : array();
			
												$args = array(
													'taxonomy' => 'product_cat',
													'hide_empty' => false,
													'parent'   => 0
												);

												$product_cat = get_terms( $args );
												foreach ($product_cat as $parent_product_cat) {
													?>
													<li class="cor_par_cat">
														<input type="checkbox" class="parent" name="cor_categories[]" id="cor_categories" value="<?php echo intval($parent_product_cat->term_id); ?>" 
														<?php 
														
														if (!empty($cor_p_cat) && in_array($parent_product_cat->term_id, $cor_p_cat)) { 
															echo 'checked';
														}
														?>
														/>
														<?php echo esc_html__($parent_product_cat->name, 'Woo-cor' ); ?>

														<?php
														$child_args         = array(
															'taxonomy' => 'product_cat',
															'hide_empty' => false,
															'parent'   => intval($parent_product_cat->term_id)
														);
														$child_product_cats = get_terms( $child_args );
														if (!empty($child_product_cats)) {
															?>
															<ul>
																<?php foreach ($child_product_cats as $child_product_cat) { ?>
																	<li class="cor_child_cat"><strong></strong>
																		<input type="checkbox" class="child parent" name="cor_categories[]" id="cor_categories" value="<?php echo intval($child_product_cat->term_id); ?>" 
																		<?php
																		if (!empty($cor_p_cat) &&in_array($child_product_cat->term_id, $cor_p_cat)) { 
																			echo 'checked';
																		}
																		?>
																		/>
																		<?php echo esc_html__($child_product_cat->name, 'Woo-cor' ); ?>

																		<?php
																		//2nd level
																		$child_args2 = array(
																			'taxonomy' => 'product_cat',
																			'hide_empty' => false,
																			'parent'   => intval($child_product_cat->term_id)
																		);

																		$child_product_cats2 = get_terms( $child_args2 );
																		if (!empty($child_product_cats2)) {
																			?>

																			<ul>
																				<?php foreach ($child_product_cats2 as $child_product_cat2) { ?>

																					<li class="child_cat">
																						<input type="checkbox" class="child parent" name="cor_categories[]" id="cor_categories" value="<?php echo intval($child_product_cat2->term_id); ?>" 
																						<?php
																						if (!empty($cor_p_cat) &&in_array($child_product_cat2->term_id, $cor_p_cat)) {
																							echo 'checked';
																						}
																						?>
																						/>
																						<?php echo esc_html__($child_product_cat2->name, 'Woo-cor' ); ?>


																						<?php
																						//3rd level
																						$child_args3 = array(
																							'taxonomy' => 'product_cat',
																							'hide_empty' => false,
																							'parent'   => intval($child_product_cat2->term_id)
																						);

																						$child_product_cats3 = get_terms( $child_args3 );
																						if (!empty($child_product_cats3)) {
																							?>

																							<ul>
																								<?php foreach ($child_product_cats3 as $child_product_cat3) { ?>

																									<li class="child_cat">
																										<input type="checkbox" class="child parent" name="cor_categories[]" id="cor_categories" value="<?php echo intval($child_product_cat3->term_id); ?>" 
																										<?php
																										if (!empty($cor_p_cat) &&in_array($child_product_cat3->term_id, $cor_p_cat)) {
																											echo 'checked';
																										}
																										?>
																										/>
																										<?php echo esc_html__($child_product_cat3->name, 'Woo-cor' ); ?>


																										<?php
																										//4th level
																										$child_args4 = array(
																											'taxonomy' => 'product_cat',
																											'hide_empty' => false,
																											'parent'   => intval($child_product_cat3->term_id)
																										);

																										$child_product_cats4 = get_terms( $child_args4 );
																										if (!empty($child_product_cats4)) {
																											?>

																											<ul>
																												<?php foreach ($child_product_cats4 as $child_product_cat4) { ?>

																													<li class="child_cat">
																														<input type="checkbox" class="child parent" name="cor_categories[]" id="cor_categories" value="<?php echo intval($child_product_cat4->term_id); ?>"
																														<?php
																														if (!empty($cor_p_cat) &&in_array($child_product_cat4->term_id, $cor_p_cat)) {
																															echo 'checked';
																														}
																														?>
																														/>
																														<?php echo esc_html__($child_product_cat4->name, 'Woo-cor' ); ?>


																														<?php
																														//5th level
																														$child_args5 = array(
																															'taxonomy' => 'product_cat',
																															'hide_empty' => false,
																															'parent'   => intval($child_product_cat4->term_id)
																														);

																														$child_product_cats5 = get_terms( $child_args5 );
																														if (!empty($child_product_cats5)) {
																															?>

																															<ul>
																																<?php foreach ($child_product_cats5 as $child_product_cat5) { ?>

																																	<li class="child_cat">
																																		<input type="checkbox" class="child parent" name="cor_categories[]" id="cor_categories" value="<?php echo intval($child_product_cat5->term_id); ?>" 
																																		<?php
																																		if (!empty($cor_p_cat) &&in_array($child_product_cat5->term_id, $cor_p_cat)) {
																																			echo 'checked';
																																		}
																																		?>
																																		/>
																																		<?php echo esc_html__($child_product_cat5->name, 'Woo-cor' ); ?>


																																		<?php
																																		//6th level
																																		$child_args6 = array(
																																			'taxonomy' => 'product_cat',
																																			'hide_empty' => false,
																																			'parent'   => intval($child_product_cat5->term_id)
																																		);

																																		$child_product_cats6 = get_terms( $child_args6 );
																																		if (!empty($child_product_cats6)) {
																																			?>

																																			<ul>
																																				<?php foreach ($child_product_cats6 as $child_product_cat6) { ?>

																																					<li class="child_cat">
																																						<input type="checkbox" class="child" name="cor_categories[]" id="cor_categories" value="<?php echo intval($child_product_cat6->term_id); ?>" 
																																						<?php
																																						if (!empty($cor_p_cat) &&in_array($child_product_cat6->term_id, $cor_p_cat)) {
																																							echo 'checked';
																																						}
																																						?>
																																						/>
																																						<?php echo esc_html__($child_product_cat6->name, 'Woo-cor' ); ?>
																																					</li>

																																				<?php } ?>
																																			</ul>

																																		<?php } ?>

																																	</li>

																																<?php } ?>
																															</ul>

																														<?php } ?>


																													</li>

																												<?php } ?>
																											</ul>

																										<?php } ?>


																									</li>

																								<?php } ?>
																							</ul>

																						<?php } ?>

																					</li>

																				<?php } ?>
																			</ul>

																		<?php } ?>

																	</li>
																<?php } ?>
															</ul>
														<?php } ?>

													</li>
													<?php
												}
												?>
											</ul>
										</div>
										<!-- for product cat -->
										<p class="description"><?php echo esc_html__( 'Choose product categories for selected action', 'Woo-cor' ); ?></p>
						</td>
					</tr>
					<tr class="">
						<th class="width_input_text">
							<div class="option-head">
								<h3>
									<?php echo esc_html__( 'Choose Tags', 'Woo-cor' ); ?>
								</h3>
							</div>
						</th>
						<td class="width_input">
									<?php
										$tag_args   = array(
											'orderby'    => 'name',
											'order'      => 'asc',
											'hide_empty' => false,
										);
										$bulk_p_tag = get_terms( 'product_tag', $tag_args );
										$p_tag      = get_post_meta( get_the_ID(), 'cor_tags', true );
										$p_tag      = is_array( $p_tag ) ? $p_tag : array();
										?>
							<select name="cor_tags[]" id="cor_tags" data-placeholder="<?php echo esc_html__('Choose Tags...', 'Woo-cor' ); ?>" class="chosen-select select_input_width product_hide_val" multiple="multiple" tabindex="-1" >;
								
								<?php
								foreach ( $bulk_p_tag as $tag ) {
									?>
										<option value="<?php echo intval( $tag->term_id ); ?>" 
										<?php
										if ( in_array( (string) $tag->term_id, (array) $p_tag, true ) ) {
											echo 'selected'; }
										?>
										>
										<?php echo esc_html__( $tag->name , 'Woo-cor' ); ?>

										</option>
									<?php
								}
								?>
							</select>
							<p class="description"><?php echo esc_html__( 'Choose product tags for selected action', 'Woo-cor' ); ?></p>
						</td>
					</tr>
					<tr class="cor_hide_bluk_p">
						<th class="width_input_text">
							<div class="option-head">
								<h3><?php echo esc_html__( 'Restriction Messages', 'Woo-cor' ); ?></h3>
							</div>
						</th>
						<td class="width_input">
						<!-- addf_cor_redirect_method -->
							<select class="afrfq_input_class" name="addf_cor_redirect_method" id="addf_cor_restriction_option">
								<option value="1" <?php selected( esc_attr(get_post_meta( get_the_ID(), 'addf_cor_redirect_method', true )), 1 ); ?>><?php echo esc_html__('Restriction Message', 'Woo-cor' ); ?></option>
								<option value="2" <?php selected( esc_attr(get_post_meta( get_the_ID(), 'addf_cor_redirect_method', true )), 2 ); ?>><?php echo esc_html__('Select Store Page', 'Woo-cor' ); ?></option>
								<option value="3" <?php selected( esc_attr(get_post_meta( get_the_ID(), 'addf_cor_redirect_method', true )), 3 ); ?>><?php echo esc_html__('Custom URL', 'Woo-cor' ); ?></option>
							</select>
							<p class="description"><?php echo esc_html__( 'Choose a option to divert user if access product directly', 'Woo-cor' ); ?></p>
						</td>
					</tr>
					<tr class="cor_hide_bluk_p">
						<th></th>
						<td>
							<select class="afrfq_input_class"  name="addf_cor_redirect_page" id="addf_cor_redirect_page">
								<?php
									$pages = get_pages();
									
									$addfl_d_redirect =get_post_meta( get_the_ID(), 'addf_cor_redirect_page', true );
								if ( !$addfl_d_redirect ) {
									$addfl_d_redirect = get_option('page_on_front');
								}
								?>
									<?php foreach ( $pages as $page ) { ?>
										<option value='<?php echo esc_attr($page->ID); ?>' <?php selected( esc_attr($addfl_d_redirect), $page->ID ); ?> ><?php echo esc_html__($page->post_title, 'Woo-cor' ); ?></option>
									<?php } ?>
							</select>
							<p class="description addf_cor_redirect_page" class="addf_cor_redirect_page"><?php echo esc_html__( 'Select a page to divert user if access product directly', 'Woo-cor' ); ?></p>
							<input class="afrfq_input_class" type="text" name="addf_cor_redirect_link" placeholder="<?php echo esc_html__('eg: www.google.com', 'Woo-cor' ); ?>" id="addf_cor_redirect_link" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'addf_cor_redirect_link', true ) , 'Woo-cor' ); ?>">
							<p class="description addf_cor_redirect_link">
							<?php 
							echo esc_html__( 'Enter a website link to redirect user if access directly.', 'Woo-cor' );
							echo '<br>';
							echo esc_html__( 'e.g www.google.com  https:// not required', 'Woo-cor' ); 
							?>
							</p>
							<textarea class="afrfq_input_class product_hide_val" type="text" name="cor_r_message" id="cor_r_message"><?php echo esc_html__( get_post_meta( get_the_ID(), 'cor_r_message', true ) , 'Woo-cor' ); ?></textarea>
							<p class="description cor_r_message"><?php echo esc_html__( 'Enter text to show if access product directly', 'Woo-cor' ); ?></p>
						</td>
					</tr>

						<tr class="hide_price_section">
						<th class="width_input_text">
							<div class="option-head">
								<h3>
									<?php echo esc_html__( 'Select Price Visibility', 'Woo-cor' ); ?>
								</h3>
							</div>

						</th>
						<td class="width_input">
							<p>
								<input class="cor_input_class cor_price_cart_uncheck" type="radio" name="cor_price_opt" id="cor_price_opt1" 
									value="show_pr" 
									<?php
									if ( empty( get_post_meta( get_the_ID(), 'cor_price_opt', true ) ) || 'show_pr' === get_post_meta( get_the_ID(), 'cor_price_opt', true ) ) {
										echo 'checked'; }
									?>
										>
								<?php echo esc_html__( 'Show Price', 'Woo-cor' ); ?>
								&nbsp;&nbsp;
								<input class="cor_input_class cor_price_cart_uncheck" type="radio" name="cor_price_opt" id="cor_price_opt2" 
									value="hide_pr" 
									<?php
									if ( 'hide_pr' === get_post_meta( get_the_ID(), 'cor_price_opt', true ) ) {
										echo 'checked'; }
									?>
								>
								<?php echo esc_html__( 'Hide Price', 'Woo-cor' ); ?>
							</p>
							<p class="description"><?php echo esc_html__( 'Choose a action for selected countries', 'Woo-cor' ); ?></p>
						</td>
						<br>
					</tr>
					
					<tr class="cor_hide_price_rule">
						<th  class="width_input_text cor_hide_price_rule">
							<div class="option-head">
								<h3> 
									<?php echo esc_html__( 'Add Text When price are Restrict ', 'Woo-cor' ); ?>
								</h3>
							 </div>
						</th>
						<td  class="width_input">
							<p>
								<input class="cor_input_class afrfq_input_class cor_hide_price_rule" type="text" name="cor_price_text" id="cor_price_text" 
									value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'cor_price_text', true ) , 'Woo-cor' ); ?>" ><br>
								<?php echo esc_html__( 'Show message when price is Restrict', 'Woo-cor' ); ?>
							 </p>
						</td>
					</tr>
					<tr class="hide_price_section">
						<th class="width_input_text">
							<div class="option-head">
								<h3>
									<?php echo esc_html__( 'Select Add to Cart Button Visibility', 'Woo-cor' ); ?>
								</h3>
							</div>

						</th>
						<td class="width_input">
							<p>
								<input class="cor_input_class cor_price_cart_uncheck" type="radio" name="cor_add_cart_opt" id="cor_add_cart_opt1" 
									value="show_cart_btn" 
									<?php
									if ( empty( get_post_meta( get_the_ID(), 'cor_add_cart_opt', true ) ) || 'show_cart_btn' === get_post_meta( get_the_ID(), 'cor_add_cart_opt', true ) ) {
										echo 'checked'; }
									?>
										>
								<?php echo esc_html__( 'Show Add to Cart', 'Woo-cor' ); ?>
								&nbsp;&nbsp;
								<input class="cor_input_class cor_price_cart_uncheck" type="radio" name="cor_add_cart_opt" id="cor_add_cart_opt2" 
									value="hide_cart_btn" 
									<?php
									if ( 'hide_cart_btn' === get_post_meta( get_the_ID(), 'cor_add_cart_opt', true ) ) {
										echo 'checked'; }
									?>
								>
								<?php echo esc_html__( 'Hide Add to Cart', 'Woo-cor' ); ?>
							</p>
							<p class="description"><?php echo esc_html__( 'Choose a action for selected countries', 'Woo-cor' ); ?></p>
						</td>
						<br>
					</tr>
							
					<tr class=" cor_check_opt_text_tr">
						<th class="width_input_text">
							<div class="option-head">
								<h3>
									<?php echo esc_html__( 'Restrict text show on Add to Cart ', 'Woo-cor' ); ?>
								</h3>
							</div>
						</th>
						<td>
								<select name="cor_hide_r_add_cart" id="cor_hide_r_add_cart" class="afrfq_input_class">
									<option value="1" <?php selected( esc_attr(get_post_meta( get_the_ID(), 'cor_hide_r_add_cart', true )), 1 ); ?>><?php echo esc_html__('Hide Add to Cart', 'Woo-cor' ); ?></option>
									<option value="2" <?php selected( esc_attr(get_post_meta( get_the_ID(), 'cor_hide_r_add_cart', true )), 2 ); ?>><?php echo esc_html__('Replace with a custom Button', 'Woo-cor' ); ?></option>
									<option value="3" <?php selected( esc_attr(get_post_meta( get_the_ID(), 'cor_hide_r_add_cart', true )), 3 ); ?>><?php echo esc_html__('Show a Message', 'Woo-cor' ); ?></option>
								</select>
								<p class="description"><?php echo esc_html__( 'Select a option for Add to Cart button to hide', 'Woo-cor' ); ?></p>
						</td>
					</tr>
					<tr class=" cor_check_opt_text_tr">
						<th>
						</th>
						<td class="width_input">
							<label class="cor_add_text" for="cor_add_text"><?php echo esc_html__('Enter Text', 'Woo-cor' ); ?> </label><br>
							<input class="cor_input_class price_hide_val afrfq_input_class cor_add_text" type="text" name="cor_add_text" id="cor_add_text" 
									value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'cor_add_text', true ) , 'Woo-cor' ); ?>" ><br>
							<p class="description cor_add_text"><?php echo esc_html__( 'Show message when Add to Cart is Restrict', 'Woo-cor' ); ?></p>

							<label class="custom_btn_add_to_cart" for="cor_text_for_replace_cart_btn"><?php echo esc_html__('Enter text for custom button', 'Woo-cor' ); ?></label><br>
							<?php 
								$cor_text_for_replace_cart_btn = get_post_meta( get_the_ID(), 'cor_text_for_replace_cart_btn', true );
							if ( '' == $cor_text_for_replace_cart_btn) {
								$cor_text_for_replace_cart_btn = 'Custom';
							}
							?>
							<input type="text" name="cor_text_for_replace_cart_btn" class="custom_btn_add_to_cart price_hide_val afrfq_input_class" value="<?php echo esc_html__(  $cor_text_for_replace_cart_btn , 'Woo-cor' ); ?>" ><br>
							<p class="description custom_btn_add_to_cart"><?php echo esc_html__( 'Text for Custom button', 'Woo-cor' ); ?></p>

							<label class="custom_btn_add_to_cart" for="cor_replace_add_to_cart_custom_btn"><?php echo esc_html__('Enter a link for Custom Button to redirect', 'Woo-cor' ); ?></label><br>
							<input type="text" class="custom_btn_add_to_cart price_hide_val afrfq_input_class" name="cor_replace_add_to_cart_custom_btn" value="<?php echo esc_html__( get_post_meta( get_the_ID(), 'cor_replace_add_to_cart_custom_btn', true ) , 'Woo-cor' ); ?>">
							<p class="custom_btn_add_to_cart  description cor_replace_add_to_cart_custom_btn"><?php echo esc_html__( 'Show Custom button when Add to Cart is restrict. https:// not required' , 'Woo-cor' ); ?></p>
						</td>
					</tr>
				</table>
			</div>
			<?php
		}
		public function cor_ex_get_product_ajax_callback() {
			$return = array(); 
			
			if (isset($_GET['q'])) {
				$search =  sanitize_text_field( wp_unslash( $_GET['q'] ));
			}
			if ( isset( $_POST['search_fields_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['q'], 'search_fields_nonce' ) ) )
				) {
					die( esc_html__('Sorry, your nonce did not verify.', 'Woo-cor' ) );
			}
			$search_results = new WP_Query(
			array(
			's'              => $search, 
			'post_type'      => 'product', 
			'post_status'    => 'publish', 
			'posts_per_page' => -1, 
			)
			);
			if ( $search_results->have_posts() ) :
				while ( $search_results->have_posts() ) :
					$search_results->the_post();
					$title    = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
					$return[] = array( $search_results->post->ID, $title ); 
				endwhile;
		endif;
			wp_send_json( $return );
		}
		public function addf_cor_setting_files() {


			include_once COR_PLUGIN_DIR . '/assets/settings/cor_general.php';
			include_once COR_PLUGIN_DIR . '/assets/settings/cor_payment_method.php';
		}
		
		public function country_payment_method_callback() {
			if ( isset( $_POST['tab'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tab'] ) ), 'tab' ) ) {
						echo '';
			}
			if ( isset( $_GET[ 'tab' ] ) ) {  
				$active_tab = sanitize_text_field($_GET[ 'tab' ]);  
			} else {
				$active_tab = 'tab_cor_general';
			}
			?>
			<?php settings_errors(); ?> 
			<h1><?php echo esc_html__('Country Restrictions Settings', 'Woo-cor' ); ?></h1>
				<h2 class="nav-tab-wrapper">  
					<a href="?post_type=country_restriction&page=addf_country_restriction_settings&tab=tab_cor_general" class="nav-tab <?php echo esc_attr($active_tab) === 'tab_cor_general' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('General Settings', 'Woo-cor'); ?></a>
					<a href="?post_type=country_restriction&page=addf_country_restriction_settings&tab=tab_cor_payment" class="nav-tab <?php echo esc_attr($active_tab) === 'tab_cor_payment' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('Payment Method Settings', 'Woo-cor'); ?></a>
				</h2>
			<form method="post" action="options.php">
				<?php
				if ( 'tab_cor_general' === $active_tab ) {  
					settings_fields( 'cor_country_general_settings' );
					do_settings_sections( 'cor_country_general_setting_section' );
				}
				if ( 'tab_cor_payment' === $active_tab ) {  
					settings_fields( 'cor_country_payment_methods' );
					do_settings_sections( 'cor_country_payment_methods_section' );
				}
				?>
				
				<?php submit_button(); ?>
			</form>
			<?php
		}


		/**
		 * Save Metadata
		 *
		 * @param For post id $post_id .
		 */
		public function cor_save_metaData( $post_id, $post  ) {
			if ( isset( $_POST['cor_nonce_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cor_nonce_field'] ) ), 'cor_field' ) ) {
						echo '';
			}
		 
			if ( isset( $_POST['cor_enable_bluk'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cor_enable_bluk'] ) ), 'cor_enable_bluk' ) ) {
						echo '';
			}
			 
			if ( isset( $_POST['cor_price_opt'] ) ) {
				update_post_meta( $post_id, 'cor_price_opt', sanitize_text_field( wp_unslash( $_POST['cor_price_opt'] ) ) );
			}

			if ( isset( $_POST['cor_add_cart_opt'] ) ) {
				update_post_meta( $post_id, 'cor_add_cart_opt', sanitize_text_field( wp_unslash( $_POST['cor_add_cart_opt'] ) ) );
			}

			if ( !isset( $_POST['cor_enable_bluk'] ) ) {
				return;
			}
			if ( isset( $_POST['cor_categories'] ) ) {
				update_post_meta( $post_id, 'cor_categories', sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['cor_categories'] ), 'post' ));
			} else {
				update_post_meta( $post_id, 'cor_categories', ' ' );
			}
			if ( isset( $_POST['cor_countries'] ) ) {
				update_post_meta( $post_id, 'cor_countries', sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['cor_countries'] ), 'post' ));
			} else {
				update_post_meta( $post_id, 'cor_countries', ' ' );
			}
			if ( isset( $_POST['bulk_restriction'] ) ) {
				update_post_meta( $post_id, 'bulk_restriction', sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['bulk_restriction'] ), 'post' ) );
			}
			if ( isset( $_POST['cor_product'] ) ) {
				update_post_meta( $post_id, 'cor_product', sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['cor_product'] ), 'post' ) );
			} else {
				update_post_meta( $post_id, 'cor_product', ' ' );
			}

			if ( isset( $_POST['cor_tags'] ) ) {
				update_post_meta( $post_id, 'cor_tags', sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['cor_tags'] ), 'post' ) );
			} else {
				update_post_meta( $post_id, 'cor_tags', ' ' );
			}
			if ( isset( $_POST['cor_payment_opt'] ) ) {
				update_post_meta( $post_id, 'cor_payment_opt', sanitize_text_field( wp_unslash( $_POST['cor_payment_opt'] ) ) );
			}
			if ( isset( $_POST['cor_vis_opt'] ) ) {
				update_post_meta( $post_id, 'cor_vis_opt', sanitize_text_field( wp_unslash( $_POST['cor_vis_opt'] ) ) );
			}
			
			if ( isset( $_POST['cor_add_pages'] ) ) {
				update_post_meta( $post_id, 'cor_add_pages', sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['cor_add_pages'] ), 'post' ) );
			}
			if ( isset( $_POST['cor_r_message'] ) ) {
				update_post_meta( $post_id, 'cor_r_message', sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['cor_r_message'] ), 'post' ) );
			}
			if ( isset( $_POST['addf_cor_redirect_page'] ) ) {
				update_post_meta( $post_id, 'addf_cor_redirect_page', sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['addf_cor_redirect_page'] ), 'post' ) );
			}
			if ( isset( $_POST['addf_cor_redirect_method'] ) ) {
				update_post_meta( $post_id, 'addf_cor_redirect_method', sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['addf_cor_redirect_method'] ), 'post' ) );
			}
			if ( isset( $_POST['addf_cor_redirect_link'] ) ) {
				update_post_meta( $post_id, 'addf_cor_redirect_link', sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['addf_cor_redirect_link'] ), 'post' ) );
			}
			if ( isset( $_POST['cor_price_text'] ) ) {
				update_post_meta( $post_id, 'cor_price_text', sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['cor_price_text'] ), 'post' ) );
			}
			if ( isset( $_POST['cor_hide_r_add_cart'] ) ) {
				update_post_meta( $post_id, 'cor_hide_r_add_cart', sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['cor_hide_r_add_cart'] ), 'post' ) );
			}
			if ( isset( $_POST['cor_add_text'] ) ) {
				update_post_meta( $post_id, 'cor_add_text', sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['cor_add_text'] ), 'post' ) );
			}
			if ( isset( $_POST['cor_replace_add_to_cart_custom_btn'] ) ) {
				update_post_meta( $post_id, 'cor_replace_add_to_cart_custom_btn', sanitize_meta( 'cor_restricted_countries', wp_unslash(( $_POST['cor_replace_add_to_cart_custom_btn'] ) ), 'post' ) );
			}
			if ( isset( $_POST['cor_text_for_replace_cart_btn'] ) ) {
				update_post_meta( $post_id, 'cor_text_for_replace_cart_btn', sanitize_meta( 'cor_restricted_countries', wp_unslash(( $_POST['cor_text_for_replace_cart_btn'] ) ), 'post' ) );
			}
			if ( isset( $_POST['cor_enable_bluk'] ) ) {
				update_post_meta( $post_id, 'cor_enable_bluk', sanitize_meta( 'cor_restricted_countries', wp_unslash( $_POST['cor_enable_bluk'] ), 'post' ) );
			}
		}

		//  ajax call to hide cart variation level
		public function addf_cor_hide_add_to_cart_price_ajax_call_back() {
			if ( isset( $_POST['variation_id'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['variation_id'] ) ), 'cor_field' ) ) {
					die( esc_html__('Sorry, your nonce did not verify.', 'Woo-cor' ) );
			}
			$variation_id                    = sanitize_text_field( wp_unslash( isset($_POST['variation_id']) ? $_POST['variation_id']  : '' ) ) ;
			$cor_visitor_current_country     = sanitize_text_field( wp_unslash( isset($_POST['current_country']) ? $_POST['current_country']  : '' ) );
			$addf_cor_variation_id_enability = get_post_meta( $variation_id , 'cor_restricted_countries_pl_cb' , true );
			$addf_cor_variation_id_countries = get_post_meta( $variation_id , 'cor_restricted_countries_pl' , true );
			$cor_pl_variation_hide_cart      = get_post_meta( $variation_id , 'cor_restricted_countries_pl_cb_hide_cart' , true );
			$addf_cor_vl_cart_op             = get_post_meta( $variation_id , 'cor_restricted_countries_pl_cb_hide_cart_msg' , true );
			if ( 'yes' == $addf_cor_variation_id_enability) {
				if ( in_array( $cor_visitor_current_country , (array) $addf_cor_variation_id_countries ) ) {
					if ( 'yes' == $cor_pl_variation_hide_cart ) {
							
						if ( '2' == $addf_cor_vl_cart_op ) {
							ob_start();
								echo esc_html__( get_post_meta( $variation_id , 'addf_cor_v_l_mthd_2' , true ) , 'Woo-cor' ); 
								$addf_cor_cart_replace = ob_get_clean();
							wp_send_json(
								array(
									'success' => 'yes',
									'addf_cor_cart_replace' => $addf_cor_cart_replace ,
									)
								);
							die('reached');
						} elseif ( '3' == $addf_cor_vl_cart_op ) {
							ob_start();
							$addf_cor_v_l_mthd_3     = get_post_meta( $variation_id , 'addf_cor_v_l_mthd_3' , true );
							$addf_cor_v_l_mthd_3_url = get_post_meta( $variation_id , 'addf_cor_v_l_mthd_3_url' , true );
							if ( empty( $addf_cor_v_l_mthd_3) ) {
								$addf_cor_v_l_mthd_3 = 'Custom Button';
							}
							?>
									<a class="button addf_cor_add_custm_btn_vl" href="<?php echo esc_url( $addf_cor_v_l_mthd_3_url ); ?>"><?php echo esc_html__( $addf_cor_v_l_mthd_3 , 'woo-cor' ); ?> </a>
								<?php
								$addf_cor_cart_replace = ob_get_clean();
								wp_send_json(
								array(
									'success' => 'yes',
									'addf_cor_cart_replace' => $addf_cor_cart_replace ,
									)
								);
								die('reached');
						} else {
							ob_start();
							echo ' ';
							$addf_cor_cart_replace = ob_get_clean();
							wp_send_json(
								array(
									'success' => 'yes',
									'addf_cor_cart_replace' => $addf_cor_cart_replace ,
									)
								);
							die('reached');
						}
							
					}
				}
			}
			wp_send_json(
				array(
					'success' => 'no',
					)
				);
				die('reached');
			
		}

		// Variable product's fields
		public function addf_cor_add_fields_in_variation_products( $loop, $variation_data, $variation) {
			global $wp_roles , $post;
			$cor_pl_selection = get_post_meta( $variation->ID, 'cor_restricted_countries_pl', true );
			?>
			<div class="">
				<table>
					<tr class="cor-option-field ">
						<td class="width_input_text">
							<div class="option-head">
								<h4>
									<?php echo esc_html__( 'Enable / Disable', 'Woo-cor' ); ?>
								</h4>
							</div>
						</td>
						<td>
							<input type="checkbox" name="cor_restricted_countries_pl_cb" class="checkbox  cor_restricted_countries_pl_cb" value="yes" <?php checked( get_post_meta( $variation->ID , 'cor_restricted_countries_pl_cb', true) , 'yes'); ?>>
							<p class="description"><?php echo esc_html__('Enable or disable variation level setiings' , 'woo-cor'); ?></p>
						</td>
					</tr>
					<tr>
						<td>
							<div class="option-head">
								<h4>
									<?php echo esc_html__( 'Choose countries', 'Woo-cor' ); ?>
								</h4>
							</div>
						</td>
						<td>
							<select  multiple="multiple" name="cor_restricted_countries_pl[]" style="width:100%;max-width: 350px;"
								data-placeholder="<?php echo esc_html__( 'Choose countries&hellip;', 'Woo-cor' ); ?>" title="<?php echo esc_html__( 'Country', 'Woo-cor' ); ?>"
								class="wc-enhanced-select" >
								<?php
								$countries = WC()->countries->get_shipping_countries();
								asort( $countries );
								if ( ! empty( $countries ) ) {
									foreach ( $countries as $key => $val ) {
										echo '<option value="' . esc_attr( $key ) . '" ' . selected( in_array( $key, (array) $cor_pl_selection, true ), true ) . '>' . esc_html__( $val , 'Woo-cor' ) . '</option>';
									}
								}
								?>
							</select>
							<p class="description"><?php echo esc_html__('Selected countries to want to hide price or Add to Cart', 'Woo-cor' ); ?></p>
						</td>
					</tr>
					<tr>
						<td>
							<p><?php echo esc_html__( 'Hide price', 'Woo-cor' ); ?></p>
						</td>
						<td>
							<input type="checkbox"  name="cor_restricted_countries_pl_cb_hide_price" class="checkbox   cor_restricted_countries_pl_cb_hide_price" value="yes" <?php checked( get_post_meta( $variation->ID , 'cor_restricted_countries_pl_cb_hide_price', true) , 'yes'); ?>>
						</td>
					</tr>
					<tr>
						<td>
							<p for="cor_restricted_countries_pl_cb_hide_price_msg"><?php echo esc_html__('Price Hide Messsage', 'Woo-cor' ); ?></p>
						</td>
						<td>
							<input type="text" class="width-40" name="cor_restricted_countries_pl_cb_hide_price_msg" value="<?php echo esc_html__(get_post_meta( $variation->ID , 'cor_restricted_countries_pl_cb_hide_price_msg' , true ) , 'woo-cor'); ?>">
						</td>
					</tr>
					<tr>
						<td>
							<p><?php echo esc_html__( 'Hide Add to Cart', 'Woo-cor' ); ?></p>
						</td>
						<td>
							<input type="checkbox"  name="cor_restricted_countries_pl_cb_hide_cart" class="checkbox  cor_restricted_countries_pl_cb_hide_cart" value="yes" <?php checked( get_post_meta( $variation->ID , 'cor_restricted_countries_pl_cb_hide_cart', true) , 'yes'); ?>>
						</td>
					</tr>
					<tr>
						<td>
							<p ><?php echo esc_html__('Add to Cart Hide method', 'Woo-cor' ); ?></p>
						</td>
						<td>
							<select name="cor_restricted_countries_pl_cb_hide_cart_msg" data-variation_id="<?php echo esc_attr($variation->ID); ?>" class="cor_restricted_countries_pl_cb_hide_cart_msg">
								<option value="1" <?php selected( get_post_meta( $variation->ID , 'cor_restricted_countries_pl_cb_hide_cart_msg' , true ) , '1' ); ?>><?php echo esc_html__('Hide Add to Cart', 'Woo-cor' ); ?></option>
								<option value="2" <?php selected( get_post_meta( $variation->ID , 'cor_restricted_countries_pl_cb_hide_cart_msg' , true ) , '2' ); ?>><?php echo esc_html__('Replace with Message', 'Woo-cor' ); ?></option>
								<option value="3" <?php selected( get_post_meta( $variation->ID , 'cor_restricted_countries_pl_cb_hide_cart_msg' , true ) , '3' ); ?>><?php echo esc_html__('Custom Button', 'Woo-cor' ); ?></option>
							</select>
						</td>
					</tr>
					<?php
					$cor_vl_option = get_post_meta( $variation->ID , 'cor_restricted_countries_pl_cb_hide_cart_msg' , true );
					?>
					<tr class=" cor_v_l_mthd_2 cor_v_l_mthd_2<?php echo esc_attr($variation->ID); ?>" style="
																		<?php 
																		if ( '2' != $cor_vl_option) {
																			echo 'display:none;';} 
																		?>
					">
						<td>
						<p ><?php echo esc_html__('Enter Message', 'Woo-cor' ); ?></p>
						</td>
						<td>
							<input type="text" name="addf_cor_v_l_mthd_2" value="<?php echo esc_html__( get_post_meta( $variation->ID , 'addf_cor_v_l_mthd_2' , true ) , 'woo-cor' ); ?>">
						</td>
					</tr>
					<tr class=" cor_v_l_mthd_3 cor_v_l_mthd_3<?php echo esc_attr($variation->ID); ?>" style="
																		<?php 
																		if ( '3' != $cor_vl_option) {
																			echo 'display:none;';} 
																		?>
					">
						<td>
						<p ><?php echo esc_html__('Enter text for custom button', 'Woo-cor' ); ?></p>
						</td>
						<td>
							<?php 
							$addf_cor_v_l_mthd_3 = get_post_meta( $variation->ID , 'addf_cor_v_l_mthd_3' , true );
							if ( '' == $addf_cor_v_l_mthd_3 ) {
								$addf_cor_v_l_mthd_3 = 'Custom Button';
							}
							?>
							<input type="text" name="addf_cor_v_l_mthd_3" value="<?php echo esc_html__($addf_cor_v_l_mthd_3 , 'woo-cor' ); ?>">
						</td>
					</tr>
					<tr class=" cor_v_l_mthd_3 cor_v_l_mthd_3<?php echo esc_attr($variation->ID); ?>" style="
																		<?php 
																		if ( '3' != $cor_vl_option) {
																			echo 'display:none;';} 
																		?>
					">
						<td>
						<p ><?php echo esc_html__('Enter url for custom button', 'Woo-cor' ); ?></p>
						</td>
						<td>
							<input type="text" name="addf_cor_v_l_mthd_3_url" value="<?php echo esc_html__(get_post_meta( $variation->ID , 'addf_cor_v_l_mthd_3_url' , true ) , 'woo-cor' ); ?>">
						</td>
					</tr>
				</table>
			</div>
			<?php
		}
		public function Afadv_save_custom_field_variations( $variation_id, $i) {
			global $wp_roles;
			if (! defined('ABSPATH') ) {
				exit; // restict for direct access
			}
			if ( isset( $_POST['cor_nonce_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cor_nonce_field'] ) ), 'cor_field' ) ) {
				echo '';
			}
			if ( isset( $_POST['cor_restricted_countries_pl_cb'] ) ) {
				$cor_pl_countries_cb = sanitize_meta( 'cor_restricted_countries_pl_cb', wp_unslash( $_POST['cor_restricted_countries_pl_cb'] ), 'post' );
			} else {
				$cor_pl_countries_cb = 'no';
			}
			update_post_meta( $variation_id, 'cor_restricted_countries_pl_cb', $cor_pl_countries_cb );
			if ( isset( $_POST['cor_restricted_countries_pl_cb_hide_price_msg'] ) ) {
				$cor_pl_countries_cb_hide_price_msg = sanitize_meta( 'cor_restricted_countries_pl_cb_hide_price_msg', wp_unslash( $_POST['cor_restricted_countries_pl_cb_hide_price_msg'] ), 'post' );
			}
			update_post_meta( $variation_id, 'cor_restricted_countries_pl_cb_hide_price_msg', $cor_pl_countries_cb_hide_price_msg );
			if ( isset( $_POST['cor_restricted_countries_pl_cb_hide_price'] ) ) {
				$cor_pl_countries_cb_hide_price = sanitize_meta( 'cor_restricted_countries_pl_cb_hide_price', wp_unslash( $_POST['cor_restricted_countries_pl_cb_hide_price'] ), 'post' );
			} else {
				$cor_pl_countries_cb_hide_price = 'no';
			}
			update_post_meta( $variation_id, 'cor_restricted_countries_pl_cb_hide_price', $cor_pl_countries_cb_hide_price );
			if ( isset( $_POST['cor_restricted_countries_pl_cb_hide_cart'] ) ) {
				$cor_restricted_countries_pl_cb_hide_cart = sanitize_meta( 'cor_restricted_countries_pl_cb_hide_cart', wp_unslash( $_POST['cor_restricted_countries_pl_cb_hide_cart'] ), 'post' );
			} else {
				$cor_restricted_countries_pl_cb_hide_cart = 'no';
			}
			update_post_meta( $variation_id, 'cor_restricted_countries_pl_cb_hide_cart', $cor_restricted_countries_pl_cb_hide_cart );
			if ( isset( $_POST['cor_restricted_countries_pl_cb_hide_cart_msg'] ) ) {
				$cor_restricted_countries_pl_cb_hide_cart_msg = sanitize_meta( 'cor_restricted_countries_pl_cb_hide_cart_msg', wp_unslash( $_POST['cor_restricted_countries_pl_cb_hide_cart_msg'] ), 'post' );
			}
			update_post_meta( $variation_id, 'cor_restricted_countries_pl_cb_hide_cart_msg', $cor_restricted_countries_pl_cb_hide_cart_msg );
			if ( isset( $_POST['cor_v_l_mthd_2'] ) ) {
				$cor_v_l_mthd_2 = sanitize_meta( 'cor_v_l_mthd_2', wp_unslash( $_POST['cor_v_l_mthd_2'] ), 'post' );
			}
			update_post_meta( $variation_id, 'cor_v_l_mthd_2', $cor_v_l_mthd_2 );
			if ( isset( $_POST['addf_cor_v_l_mthd_3'] ) ) {
				$addf_cor_v_l_mthd_3 = sanitize_meta( 'addf_cor_v_l_mthd_3', wp_unslash( $_POST['addf_cor_v_l_mthd_3'] ), 'post' );
			}
			update_post_meta( $variation_id, 'addf_cor_v_l_mthd_3', $addf_cor_v_l_mthd_3 );
			if ( isset( $_POST['addf_cor_v_l_mthd_3_url'] ) ) {
				$addf_cor_v_l_mthd_3_url = sanitize_meta( 'addf_cor_v_l_mthd_3_url', wp_unslash( $_POST['addf_cor_v_l_mthd_3_url'] ), 'post' );
			}
			update_post_meta( $variation_id, 'addf_cor_v_l_mthd_3_url', $addf_cor_v_l_mthd_3_url );
			if ( isset( $_POST['cor_restricted_countries_pl'] ) ) {
				$cor_pl_countries = sanitize_meta( 'cor_restricted_countries_pl', wp_unslash( $_POST['cor_restricted_countries_pl'] ), 'post' );
			} else {
				$cor_pl_countries = array();
			}
			update_post_meta( $variation_id, 'cor_restricted_countries_pl', $cor_pl_countries );
		}
	}
	// end class cor_Admin.
	new Cor_Admin();
}
