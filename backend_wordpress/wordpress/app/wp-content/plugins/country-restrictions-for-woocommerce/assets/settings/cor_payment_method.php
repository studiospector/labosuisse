<?php

add_settings_section(

	'payment_method_setting',             // ID

	esc_html__( 'Payment Method Settings', 'Woo-cor' ),     // Title .

	'cor_country_restriction_methods_callback',                                 // Callback

	'cor_country_payment_methods_section'           // Page on which to add this section of options.

);


// resgister the payment gateway options
$payment_gateways_obj = new WC_Payment_Gateways();

$enabled_payment_gateways = $payment_gateways_obj->payment_gateways();

foreach ( $enabled_payment_gateways as $key => $payment_gateway ) {
	if (empty( $payment_gateway->title ) ) {
		continue;
	}
	$cor_countries_payment_method = 'cor_countries_payment_method_' . $payment_gateway->id;
	$cor_payment_enablility       = 'cor_payment_enablility_' . $payment_gateway->id;

	register_setting( 'cor_country_payment_methods', esc_attr($cor_countries_payment_method) );
	register_setting( 'cor_country_payment_methods', esc_attr($cor_payment_enablility) );	
}
 
function cor_country_restriction_methods_callback() {

	?>

	<table class="form-table">

			

		<?php

		$payment_gateways_obj = new WC_Payment_Gateways();

		$enabled_payment_gateways = $payment_gateways_obj->payment_gateways();
		
		foreach ( $enabled_payment_gateways as $key1 => $payment_gateway ) {
			$name = $payment_gateway->id;
			?>

			<tr valign="top" >

			<?php

			if ( !empty( $payment_gateway->id) ) {
				
				$cor_payment_enable = "cor_payment_enablility_{$name}";
				
				$cor_countries_ = 'cor_countries_payment_method_' . $name;
				?>

					<th  scope="row">

					<div class="option-head">

						<h3>

						<?php echo esc_html__( $payment_gateway->title, 'Woo-cor' ); ?>

						</h3>

					</div>

					</th>

					<td  style="width: 20%;">

						
						<input class="reset_all_payment_methods" type="radio" name="<?php echo esc_attr($cor_payment_enable); ?>" value="1" <?php checked( get_option( 'cor_payment_enablility_' . $name ), '1' ); ?> id="enable_payment_for_countries_<?php echo esc_html__($name); ?>">

						<label for="enable_payment_for_countries_<?php echo esc_html__($name); ?>"><?php echo esc_html__('Enable', 'Woo-cor' ); ?></label>&nbsp;&nbsp;

						<input class="reset_all_payment_methods" type="radio" name="<?php echo esc_attr($cor_payment_enable); ?>" value="2" <?php checked( get_option( 'cor_payment_enablility_' . $name ), '2' ); ?>  id="diable_payment_for_countries_<?php echo esc_html__($name); ?>">

						<label for="diable_payment_for_countries_<?php echo esc_html__($name); ?>"><?php echo esc_html__('Disable', 'Woo-cor' ); ?></label>

					</td>

					<td style="text-align:left;">

					<select name="<?php echo esc_attr($cor_countries_); ?>[]" id="cor_countries" data-placeholder="<?php echo esc_html__('Choose Countries...', 'Woo-cor' ); ?>" class="chosen-select country_input_width" multiple="multiple" tabindex="-1">;

					<?php

					$countr_obj = new WC_Countries();

					$countries = $countr_obj->__get( 'countries' );

					foreach ( $countries as $key => $country ) {

						?>

						<option value="<?php echo esc_html( $key ); ?>" 

						<?php

						if ( in_array( $key, (array) get_option($cor_countries_ ), true ) ) {

							echo 'selected'; }

						?>

						>

						<?php echo esc_html__( $country , 'Woo-cor' ); ?>

						</option>

						<?php

					}

					?>

				</select>

				<p class="description"><?php echo esc_html__( 'Geo-location must be enabled', 'Woo-cor' ); ?></p >

				<p class="description"><?php echo esc_html__( 'Select countries to enable/disable payment method', 'Woo-cor' ); ?></p>
					</td>

				<?php

			}

			?>

			</tr>

			<?php

		}

		?>

	</table>

	<?php

}   
