<?php
// cor_country_general_settings
	add_settings_section(
		'general_setting',             // ID
		esc_html__( 'General Settings', 'Woo-cor' ),     // Title .
		'cor_country_general_settings_callback',                                 // Callback
		'cor_country_general_setting_section'           // Page on which to add this section of options.
	);

	register_setting( 'cor_country_general_settings', 'cor_unselected_couteries_shop_method' );
	register_setting( 'cor_country_general_settings', 'cor_redirect_unselected_c_message' );
	register_setting( 'cor_country_general_settings', 'cor_hide_show_add_to_cart_button_setting' );
	register_setting( 'cor_country_general_settings', 'cor_button_replace_options_a_t_c' );
	register_setting( 'cor_country_general_settings', 'cor_custom_button_text_a_t_c' );
	register_setting( 'cor_country_general_settings', 'cor_hide_btn_custom_message_a_t_c' );
	register_setting( 'cor_country_general_settings', 'cor_custom_button_link_a_t_c' );
	register_setting( 'cor_country_general_settings', 'cor_custom_button_message_a_t_c' );
	register_setting( 'cor_country_general_settings', 'cor_hide_show_product_price_setting' );
	register_setting( 'cor_country_general_settings', 'cor_hide_price_text' );

	function cor_country_general_settings_callback() {
		$cor_unselected_counteries           = get_option('cor_unselected_couteries_shop_method');
		$cor_add_to_cart_counteries          = get_option('cor_hide_show_add_to_cart_button_setting');
		$cor_hide_show_product_price_setting = get_option('cor_hide_show_product_price_setting');
		if ( '' == $cor_unselected_counteries) {
			$cor_unselected_counteries = '1';
		}
		?>
		<table class="form-table">
			<tr class="cor-option-field ">
				<th>
					<h3>
				   <?php echo esc_html__(' Default catalog visibility for Products', 'Woo-cor' ); ?>
				</h3>
			</th>
				<td>
					<input type="radio" name="cor_unselected_couteries_shop_method" value="1" <?php checked( $cor_unselected_counteries, '1' ); ?> id="cor_unselected_method_1">
					<label for="cor_unselected_method_1"><?php echo esc_html__('Show all products', 'Woo-cor' ); ?></label>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="cor_unselected_couteries_shop_method" value="2" <?php checked( $cor_unselected_counteries, '2' ); ?> id="cor_unselected_method_2">
					<label for="cor_unselected_method_2"><?php echo esc_html__('Hide all products', 'Woo-cor' ); ?></label>

					<p class="description"><?php echo esc_html__('Select default product visibility for all unselected countries', 'Woo-cor' ); ?></p>
				</td>
			</tr>
				<tr class="cor-option-field all_redirect_methods_cor_general_settings">
					<th>
						<h3>
					<?php echo esc_html__(' Message for unselected countries', 'Woo-cor' ); ?>
					</h3>
				</th>
					<td>
						<textarea  class="cor_redirect_unselected_c_message general_settings_text_size_text_area" placeholder="<?php echo esc_html__('Enter a message to show unselected countries if access directly', 'Woo-cor' ); ?>"  name="cor_redirect_unselected_c_message" id="" cols="30" rows="10"><?php echo esc_html__(get_option('cor_redirect_unselected_c_message'), 'Woo-cor' ); ?></textarea>
						<p class="description cor_redirect_unselected_c_message"><?php echo esc_html__('Enter message to show unselected  countries if access directly', 'Woo-cor' ); ?></p>
					</td>
				</tr>
				<tr class="cor-option-field ">
				<th>
					<h3>
				   <?php echo esc_html__(' Default Add to Cart visibility', 'Woo-cor' ); ?>
				  </h3>
				</th>
				<td>
					<input type="radio" name="cor_hide_show_add_to_cart_button_setting" value="show_cart_btn"  <?php echo esc_attr( 'show_cart_btn' == $cor_add_to_cart_counteries || !$cor_add_to_cart_counteries ? 'checked' : '' ); ?> id="cor_hide_add_to_cart_global_1">
					<label for="cor_cart_method"><?php echo esc_html__('Show Add to cart button', 'Woo-cor' ); ?></label>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="cor_hide_show_add_to_cart_button_setting" value="hide_cart_btn" <?php checked( $cor_add_to_cart_counteries, 'hide_cart_btn' ); ?> id="cor_hide_add_to_cart_global_2">
					<label for="cor_cart_method"><?php echo esc_html__('Hide all Add to Cart', 'Woo-cor' ); ?></label>

					<p class="description"><?php echo esc_html__('Select default Add To Cart visibility for all unselected countries', 'Woo-cor' ); ?></p>
				</td>
			</tr>
			<tr class="cor-option-field replace_button_select_options ">
				<th>
					<h3>
				   <?php echo esc_html__('Restrict text show on Add to Cart', 'Woo-cor' ); ?>
				 </h3>
				</th>
				<td class="">
					<select id="replace_button_select_options" class=" replace_select_options general_input_class"  name="cor_button_replace_options_a_t_c" >
					<option value="hide_btn" <?php echo selected( 'hide_btn', get_option( 'cor_button_replace_options_a_t_c' ) ); ?>><?php esc_html_e( 'Hide Add to Cart', 'Woo-cor' ); ?></option>
					<option value="replace_btn" <?php echo selected( 'replace_btn', get_option( 'cor_button_replace_options_a_t_c' ) ); ?>><?php esc_html_e( 'Replace with a Custom button', 'Woo-cor' ); ?></option>
					<option value="msg_btn" <?php echo selected( 'msg_btn', get_option( 'cor_button_replace_options_a_t_c' ) ); ?>><?php esc_html_e( 'Show a Message', 'Woo-cor' ); ?></option>
					
				</select>

				</td>
			</tr>
			 <tr class="replace_button_select_options ">
						<th>
						</th>
						<td class="width_input hide_all_div">
							<label class="cstm_message_option_button_hhide" for="cor_hide_btn_custom_message_a_t_c"><?php echo esc_html__('Enter Text', 'Woo-cor' ); ?> </label><br>
							<input class="cstm_message_option_button_hhide general_input_class" type="text" name="cor_hide_btn_custom_message_a_t_c" id="cor_hide_btn_custom_message_a_t_c" 
									value="<?php echo esc_html__( get_option('cor_hide_btn_custom_message_a_t_c')); ?>" ><br>
							<p class="description  cstm_message_option_button_hhide"><?php echo esc_html__( 'Show message when Add to Cart is Restrict', 'Woo-cor' ); ?></p>

							<label class="custom_btn_link_message" for="cor_custom_button_message_a_t_c"><?php echo esc_html__('Enter text for custom button', 'Woo-cor' ); ?></label><br>
							<?php 
								$cor_custom_button_message_a_t_c = get_option('cor_custom_button_message_a_t_c');
							if ( '' == $cor_custom_button_message_a_t_c) {
								$cor_custom_button_message_a_t_c = 'Custom';
							}
							?>
							<input type="text" name="cor_custom_button_message_a_t_c" class="custom_btn_link_message general_input_class" value="<?php echo esc_html__(  $cor_custom_button_message_a_t_c , 'Woo-cor' ); ?>" ><br>
							<p class="custom_btn_link_message"><?php echo esc_html__( 'Text for Custom button', 'Woo-cor' ); ?></p>

							<label class="custom_btn_link_message " for="cor_custom_button_link_a_t_c"><?php echo esc_html__('Enter a link for Custom Button to redirect', 'Woo-cor' ); ?></label><br>
							<input type="text" class="custom_btn_link_message general_input_class" name="cor_custom_button_link_a_t_c" value="<?php echo esc_html__( get_option('cor_custom_button_link_a_t_c') ); ?>">
							<p class="custom_btn_link_message"><?php echo esc_html__( 'Show Custom button when Add to Cart is restrict. https:// not required' , 'Woo-cor' ); ?></p>
						</td>
					</tr>
					<tr class="cor-option-field">
				<th>
					<h3>
				   <?php echo esc_html__('Default Price visibility', 'Woo-cor' ); ?>
				  </h3>
				</th>
				<td>
					<input type="radio" name="cor_hide_show_product_price_setting" value="show_pr" <?php echo esc_attr('show_pr' == $cor_hide_show_product_price_setting || !$cor_hide_show_product_price_setting ? 'checked' : '' ); ?> class="cor_hide_price_global_1">
					<label for="cor_cart_method"><?php echo esc_html__('Show Prices', 'Woo-cor' ); ?></label>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="cor_hide_show_product_price_setting" value="hide_pr" <?php checked( $cor_hide_show_product_price_setting, 'hide_pr' ); ?> class="cor_hide_price_global_1">
					<label for="cor_cart_method"><?php echo esc_html__('Hide Prices', 'Woo-cor' ); ?></label>

					<p class="description"><?php echo esc_html__('Select default Add To Cart visibility for all unselected countries', 'Woo-cor' ); ?></p>
				</td>
			</tr>
			<tr class="cor_hide_price_global">
						<th  class="cor_hide_price_global">
							<div class="option-head">
								<h3> 
									<?php echo esc_html__( 'Add Text When price are Hide', 'Woo-cor' ); ?>
								</h3>
							 </div>
						</th>
						<td  class="width_input">
							<p>
								<input class="cor_hide_price_global general_input_class" type="text" name="cor_hide_price_text" id="cor_hide_price_text" 
									value="<?php echo esc_html__( get_option('cor_hide_price_text') ); ?>" ><br>
								<?php echo esc_html__( 'Show message when price is Hide', 'Woo-cor' ); ?>
							 </p>
						</td>
					</tr>
		</table>
		<!-- cor_custom_button_message_a_t_c -->
		<?php
	}
