<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section id="cbr_content5" class="cbr_tab_section">
    <div class="cbr_tab_inner_container">
		<h1 class="tab_main_heading"><?php esc_html_e( 'Payment Restrictions', 'country-base-restrictions-pro-addon' ); ?></h1>
		<form method="post" id="cbr_checkout_restrictions_tab_form" autocomplete="off">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<td class="bulk-data-table">
							<div class="cbr_checkout_list" data-list="<?php echo esc_attr( wp_json_encode( get_option( 'cbr_checkout_restrictions') ) ); ?>" >
							<input type="hidden" name="action" value="save_cbr_checkout_restrictions">
							<?php wp_nonce_field( 'cbr_checkout_restrictions_form_action', 'cbr_checkout_restrictions_form_nonce_field' ); ?>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="submit cbr-btn ck_cbr_add_wrap">
				<button type="button" class="button button-primary btn-ck-cbr-add"><?php esc_html_e( 'Add New Restriction Rule', 'country-base-restrictions-pro-addon' ); ?> <span class="dashicons dashicons-plus"></span></button>
			</div>
		</form>
		<script type="text/html" id="tmpl-cbr-checkout-template">
			<div class="Accordion ck_cbr_row">
			 	<div class="ck_cbr_accordion_handle">
					<div class="clickDiv"></div>
					<span class="ck_cbr_row_title">
						<div class="ck_cbr_row_toggle_handle" style="">
							<input type="hidden" name="data[{{{ data.index }}}][ck_rule_toggle]" value="disabled"/>
							<input class="tgl tgl-flat-cbr ck_rule_toggle" id="ck_rule_toggle_{{{ data.index }}}" name="data[{{{ data.index }}}][ck_rule_toggle]" type="checkbox" <# if ( 'enabled' === data.list.ck_rule_toggle ) { #>checked <# } #> value="enabled"/>
							<label class="tgl-btn" for="ck_rule_toggle_{{{ data.index }}}"></label>
						</div>
						<span class="ck_cbr_row_title_title" style="display: inline-block;">
							<# if ( data.list.label_name === '') {#>Restrictions Rule {{{ data.index }}} <#} else {#> {{{ data.list.label_name }}}<# } #>
						</span>
						<span class="ck_cbr_row_title_note" style="display: inline-block;">
							<# if ( data.list.restriction_by === 'categories') {#>By Categories<# } #>
						</span>
					</span>
					<div class="submit cbr-btn ck_cbr_save_wrap" style="display:none;">
						<div class="spinner" style="float:none"></div>
						<button type="submit" class="button button-primary btn-ck-cbr-save"><?php esc_html_e( 'Save & Close', 'country-base-restrictions-pro-addon' ); ?></button>
					</div>
					<div class="ck_cbr_row_sort_handle ck_controller"><span class="dashicons dashicons-menu"></span></div>
					<div class="ck_cbr_row_remove_handle ck_controller"><span class="dashicons dashicons-no-alt btn-ck-cbr-delete" title="Delete"></span></div>
					<div class="ck_cbr_row_duplicate_handle ck_controller"><span class="dashicons dashicons-admin-page" title="Duplicate"></span></div>				
				</div>
				<div class="ck_cbr_single" data-key="{{{ data.index }}}" style="display:none;">				
					<div class="" style="padding: 0;">
						<div class='ck-cbr-block'>
							<div id="label_name" class='Label_Name ck_cbr_single_col'>
								<label for="label_name"><?php esc_html_e( 'Label Name', 'country-base-restrictions-pro-addon' ); ?></label>
								<input type="text" class="label_name" id="label_name_{{{ data.index }}}" name="data[{{{ data.index }}}][label_name]" value="{{{ data.list.label_name }}}" autocomplete="false" style="">
							</div>
							<div class='Customer_Country ck_cbr_single_col'>
								<label for="customer_country"><?php esc_html_e( 'Customer Country', 'country-base-restrictions-pro-addon' ); ?></label>
								<select class="customer_country" id="customer_country_{{{ data.index }}}" name=data[{{{ data.index }}}][customer_country] value="{{{ data.list.customer_country }}}" autocomplete="false" class="wc-enhanced-select" style="">
									<option value="billing" selected="selected" ><?php esc_html_e( 'Billing Country', 'country-base-restrictions-pro-addon' ); ?></option>
									<option value="shipping" <# if ( 'shipping' === data.list.customer_country ) { #>selected="selected"<# } #>><?php esc_html_e( 'Shipping Country', 'country-base-restrictions-pro-addon' ); ?></option>
								</select>
							</div>
							<div class='Payment_Methods ck_cbr_single_col'>
								<label for="payment_methods"><?php esc_html_e( 'Payment Methods', 'country-base-restrictions-pro-addon' ); ?></label>
								<?php
								$payment_methods = WC()->payment_gateways->payment_gateways();
								?><select multiple class="select2 payment_methods" id="payment_methods_{{{ data.index }}}" name=data[{{{ data.index }}}][payment_methods][] autocomplete="false" title="<?php esc_attr_e( 'Payment method', 'woocommerce' ) ?>"
									class="wc-enhanced-select" style="">
								<?php
								if ( ! empty($payment_methods) ) { ?>
									<?php foreach ((array) $payment_methods as $id => $Method ) { ?>
										<option value="<?php echo $id; ?>"  <# if ( inArray('<?php echo $id; ?>', data.list.payment_methods ) ) { #>selected="selected"<# } #>><?php echo $Method->title; ?></option>
									<?php }
								}
								?></select>
							</div>
							<div class='Restriction_Rule ck_cbr_single_col'>
								<label for="restriction_rule"><?php esc_html_e( 'Restriction Rule', 'country-base-restrictions-pro-addon' ); ?></label>
								<select class="restriction_rule" id="restriction_rule_{{{ data.index }}}" name=data[{{{ data.index }}}][restriction_rule] value="{{{ data.list.restriction_rule }}}" autocomplete="false" class="wc-enhanced-select" style="">
									<option value="include" selected="selected" ><?php esc_html_e( 'Include', 'country-base-restrictions-pro-addon' ); ?></option>
									<option value="exclude" <# if ( 'exclude' === data.list.restriction_rule ) { #>selected="selected"<# } #>><?php esc_html_e( 'Exclude', 'country-base-restrictions-pro-addon' ); ?></option>
								</select>
							</div>
							<div class='Restrict_Countries ck_cbr_single_col'>
								<label for="restrict_countries"><?php esc_html_e( 'Select Countries', 'country-base-restrictions-pro-addon' ); ?></label>
								<?php
									$countries_obj   = new WC_Countries();
									$countries   = $countries_obj->__get('countries');
									asort( $countries );
									 ?><select multiple class="select2 restrict_countries" id="restrict_countries_{{{ data.index }}}" name=data[{{{ data.index }}}][restrict_countries][] data-placeholder="<?php esc_attr_e( 'Choose countries...', 'woocommerce' ); ?>" autocomplete="false" title="<?php esc_attr_e( 'Country', 'woocommerce' ) ?>"
										class="wc-enhanced-select" style="">
										<?php
									if ( ! empty( $countries ) ) {
										foreach ( $countries as $key => $val ) { ?>
											<option value="<?php echo $key; ?>" <# if ( inArray('<?php echo $key; ?>', data.list.restrict_countries ) ) { #>selected="selected"<# } #>><?php echo $val; ?></option> <?php
										}
									}
								?>
									</select>
									
									<?php
									if( empty( $countries ) ) {
										echo "<p><b>" . esc_html( "You need to setup shipping locations in WooCommerce settings", 'country-base-restrictions-pro-addon') . " <a href='admin.php?page=wc-settings'> " . esc_html( "HERE", 'country-base-restrictions-pro-addon' ) . "</a> " . esc_html( "before you can choose country restrictions", 'country-base-restrictions-pro-addon' ) . "</b></p>";
									} ?>
							</div>						
						</div>
					</div>
				</div>
			</div>
		</script>
	</div>
</section>