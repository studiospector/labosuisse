<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section id="cbr_content2" class="cbr_tab_section">
    <div class="cbr_tab_inner_container">
		<h1 class="tab_main_heading"><?php esc_html_e( 'Catalog Restrictions', 'country-base-restrictions-pro-addon' ); ?></h1>
		<form method="post" id="cbr_bulk_restrictions_tab_form" autocomplete="off">
			<table class="form-table">
			<tbody>
				<tr valign="top">
					<td class="bulk-data-table">
						<div class="cbr_list" data-list="<?php echo esc_attr( wp_json_encode( get_option( 'cbr_bulk_restrictions') ) ); ?>" >
						<input type="hidden" name="action" value="save_cbr_bulk_restrictions">
						<?php wp_nonce_field( 'cbr_bulk_restrictions_form_action', 'cbr_bulk_restrictions_form_nonce_field' ); ?>
						</div>
					</td>
				</tr>
			 </tbody>
			</table>
			<div class="submit cbr-btn cbr_add_wrap">
				<button type="button" class="button button-primary btn-cbr-add"><?php esc_html_e( 'Add New Restriction Rule', 'country-base-restrictions-pro-addon' ); ?> <span class="dashicons dashicons-plus"></span></button>
			</div>
		</form>
		<script type="text/html" id="tmpl-cbr-template">
			<div class="accordion br_cbr_row">
				<div class="br_cbr_accordion_handle">
					<div class="clickDiv"></div>
					<span class="br_cbr_row_title">
						<div class="br_cbr_row_toggle_handle" style="">
							<input type="hidden" name="data[{{{ data.index }}}][field_exclusivity]" value="disabled"/>
							<input class="tgl tgl-flat-cbr field_exclusivity" id="field_exclusivity_{{{ data.index }}}" name="data[{{{ data.index }}}][field_exclusivity]" type="checkbox" <# if ( 'enabled' === data.list.field_exclusivity ) { #>checked <# } #> value="enabled"/>
							<label class="tgl-btn" for="field_exclusivity_{{{ data.index }}}"></label>
						</div>
						<span class="br_cbr_row_title_title" style="display: inline-block;">
							<# if ( data.list.private_note === '') {#>Restrictions Rule {{{ data.index }}} <#} else {#> {{{ data.list.private_note }}}<# } #>
						</span>
						<span class="br_cbr_row_title_note" style="display: inline-block;">
							<# if ( data.list.restriction_by === 'categories') {#>By Categories<# } #>
							<# if ( data.list.restriction_by === 'tags') {#>By Tags<# } #>
							<# if ( data.list.restriction_by === 'attributes') {#>By Attributes<# } #>
							<# if ( data.list.restriction_by === 'shipping-class') {#>By Shipping class<# } #>
							<# if ( data.list.restriction_by === 'global') {#>By Global<# } #>
						</span>
					</span>
					<div class="submit cbr-btn cbr_save_wrap" style="display:none;">
						<div class="spinner" style="float:none"></div>
						<button type="submit" class="button button-primary btn-save"><?php esc_html_e( 'Save & Close', 'country-base-restrictions-pro-addon' ); ?></button>
					</div>
					<div class="br_cbr_row_sort_handle controller"><span class="dashicons dashicons-menu"></span></div>
					<div class="br_cbr_row_remove_handle controller"><span class="dashicons dashicons-no-alt btn-cbr-delete" title="Delete"></span>
				</div>
				<div class="br_cbr_row_duplicate_handle controller"><span class="dashicons dashicons-admin-page" title="Duplicate"></span></div>				
			</div>
			<div class="cbr_single" data-key="{{{ data.index }}}" style="display:none;">				
				<div class="" style="padding: 0;">
					<div class='cbr-block'>
						<div id="private_note" class='cbr_single_col'>
							<label for="private_note"><?php esc_html_e( 'Rule Name', 'country-base-restrictions-pro-addon' ); ?><span class="woocommerce-help-tip tipTip"title="<?php esc_html_e( 'Add a name for your rule, only for admin use.', 'country-base-restrictions-pro-addon' ); ?>"></span></label>
							<input type="text" class="private_note" id="private_note_{{{ data.index }}}" name="data[{{{ data.index }}}][private_note]" value="{{{ data.list.private_note }}}" autocomplete="false" style="">
						</div>						
					</div>
					<div class='cbr-block'>
						<div class="sub-block">
							<div class='restriction-by cbr_single_col'>
								<label for="restriction_by"><?php esc_html_e( 'Restriction By', 'country-base-restrictions-pro-addon' ); ?><span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( 'Restrict products by categories, tags, attributes and shipping class.', 'country-base-restrictions-pro-addon' ); ?>"></span></label>
								<select class="select2 restriction_by" id="restriction_by_{{{ data.index }}}" name=data[{{{ data.index }}}][restriction_by] value="{{{ data.list.restriction_by }}}" autocomplete="false" class="wc-enhanced-select" style="">
									<option value="categories" <# if ( 'categories' === data.list.restriction_by ) { #>selected="selected" <# } #>><?php esc_html_e( 'Categories', 'woocommerce' ); ?></option>
									<option value="tags" <# if ( 'tags' === data.list.restriction_by ) { #>selected="selected"<# } #>><?php esc_html_e( 'Tags', 'woocommerce' ); ?></option>
									<option value="attributes" <# if ( 'attributes' === data.list.restriction_by ) { #>selected="selected"<# } #>><?php esc_html_e( 'Attributes', 'woocommerce' ); ?></option>
									<option value="shipping-class" <# if ( 'shipping-class' === data.list.restriction_by ) { #>selected="selected"<# } #>><?php esc_html_e( 'Shipping class', 'woocommerce' ); ?></option>
									<option value="global" <# if ( 'global' === data.list.restriction_by ) { #>selected="selected"<# } #>><?php esc_html_e( 'Global', 'woocommerce' ); ?></option>
								</select>
							</div>
							<div class='select_categories cbr_single_col'>
								<label for="select_category"><?php esc_html_e( 'Select Categories', 'country-base-restrictions-pro-addon' ); ?></label>
								<?php 
									$cat_args = array(
										'taxonomy' => 'product_cat',
										'orderby'    => 'name',
										'order'      => 'ASC',
										'hide_empty' => false,
									);
									 
									$product_categories = get_terms( $cat_args );
									
									 ?><select multiple class="select2 selected_category" id="selected_category_{{{ data.index }}}" name=data[{{{ data.index }}}][selected_category][] data-placeholder="<?php esc_attr_e( 'Choose categories...', 'woocommerce' ); ?>" autocomplete="false" title="<?php esc_attr_e( 'Category', 'woocommerce' ) ?>"
										class="wc-enhanced-select" style="">
									 <?php
									if( !empty($product_categories) ) {
										foreach ($product_categories as $key => $category) {?>
											<option value="<?php echo $category->slug; ?>"  <# if ( inArray('<?php echo $category->slug; ?>', data.list.selected_category ) ) { #>selected="selected"<# } #>><?php echo $category->name; ?></option>
										<?php }
									}
									?></select>
							</div>
							<div class='select_tags cbr_single_col'>
								<label for="select_category"><?php esc_html_e( 'Select Tags', 'country-base-restrictions-pro-addon' ); ?></label>
								<?php
								$terms = get_terms( 'product_tag' );
								?><select multiple class="select2 selected_tag" id="selected_tag_{{{ data.index }}}" name=data[{{{ data.index }}}][selected_tag][] data-placeholder="<?php esc_attr_e( 'Choose tags...', 'woocommerce' ); ?>" autocomplete="false" title="<?php esc_attr_e( 'Tags', 'woocommerce' ) ?>"
									class="wc-enhanced-select" style="">
								<?php
								if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
									foreach ( $terms as $term ) { ?>
										<option value="<?php echo $term->slug; ?>"  <# if ( inArray('<?php echo $term->slug; ?>', data.list.selected_tag ) ) { #>selected="selected"<# } #>><?php echo $term->name; ?></option>
									<?php }
								}
								?></select>
							</div>
							<div class='select_shipping_class cbr_single_col'>
								<label for="select_shipping_class"><?php esc_html_e( 'Select Shipping classs', 'country-base-restrictions-pro-addon' ); ?></label>
								<?php
								$shipping_classes = get_terms( array( 'taxonomy' => 'product_shipping_class', 'hide_empty' => false ) );
								?><select multiple class="select2 selected_shipping_class" id="selected_shipping_class_{{{ data.index }}}" name=data[{{{ data.index }}}][selected_shipping_class][] autocomplete="false" title="<?php esc_attr_e( 'Shipping class', 'woocommerce' ) ?>"
									class="wc-enhanced-select" style="">
								<?php
								if ( ! empty($shipping_classes) ){ ?>
									<option value=""><?php _e( 'No shipping class', 'woocommerce' ); ?></option>
									<?php foreach ((array) $shipping_classes as $key => $the_class ) { ?>
										<option value="<?php echo $the_class->term_id; ?>"  <# if ( inArray('<?php echo $the_class->term_id; ?>', data.list.selected_shipping_class ) ) { #>selected="selected"<# } #>><?php echo $the_class->name; ?></option>
									<?php }
								}
								?></select>
							</div>
							<div class='select_attribute cbr_single_col'>
								<label for="selected_attribute"><?php esc_html_e( 'Select Attributes', 'country-base-restrictions-pro-addon' ); ?></label>
								<?php
								$attribute_taxonomies = wc_get_attribute_taxonomies();
								?><select multiple class="select2 selected_attribute" id="selected_attribute_{{{ data.index }}}" name=data[{{{ data.index }}}][selected_attribute][] data-placeholder="<?php esc_attr_e( 'Choose attribute...', 'woocommerce' ); ?>" autocomplete="false" title="<?php esc_attr_e( 'Attribute', 'woocommerce' ) ?>"
									class="wc-enhanced-select" style="">
								<?php
								if ( ! empty( $attribute_taxonomies ) ){ 
									foreach ((array) $attribute_taxonomies as $key => $attribute ) {?>
										<optgroup label="<?php echo $attribute->attribute_label; ?>">
										<?php $values = get_terms( 'pa_' . $attribute->attribute_name, array( 'hide_empty' => false ) );
										
										foreach ((array) $values as $key => $value ) { ?>
												<option value="<?php echo $value->term_id; ?>"  <# if ( inArray('<?php echo $value->term_id; ?>', data.list.selected_attribute ) ) { #>selected="selected"<# } #>><?php echo $attribute->attribute_label; echo '-'; echo $value->name; ?></option>
										<?php } ?>
										</optgroup>
									<?php }
								}
								?></select>
							</div>
						</div>
						<div class="sub-block">
							<div class='select_geo_availability cbr_single_col'>
								<label for="geographic_availability"><?php esc_html_e( 'Restriction Rule', 'country-base-restrictions-pro-addon' ); ?><span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( 'Select if to include only the selected countries or to exclude the selected countries.', 'country-base-restrictions-pro-addon' ); ?>"></span></label>
								<select class="select2 geographic_availability" id="geographic_availability_{{{ data.index }}}" name=data[{{{ data.index }}}][geographic_availability] value="{{{ data.list.geographic_availability }}}" autocomplete="false" class="wc-enhanced-select" style="">
									<option value="specific" selected="selected" ><?php esc_html_e( 'Selected countries only', 'country-base-restrictions-pro-addon' ); ?></option>
									<option value="excluded" <# if ( 'excluded' === data.list.geographic_availability ) { #>selected="selected"<# } #>><?php esc_html_e( 'Excluding selected countries', 'country-base-restrictions-pro-addon' ); ?></option>
								</select>
							</div>
							<div class='select_restricted_countries cbr_single_col'>
								<label for="selected_countries"><?php esc_html_e( 'Select Countries', 'country-base-restrictions-pro-addon' ); ?></label>
								<?php
									$countries_obj   = new WC_Countries();
									$countries   = $countries_obj->__get('countries');
									asort( $countries );
									?><select multiple class="select2 selected_countries" id="selected_countries_{{{ data.index }}}" name=data[{{{ data.index }}}][selected_countries][] data-placeholder="<?php esc_attr_e( 'Choose countries...', 'woocommerce' ); ?>" autocomplete="false" title="<?php esc_attr_e( 'Country', 'woocommerce' ) ?>"
										class="wc-enhanced-select" style="">
										<?php
										if ( ! empty( $countries ) ) {
											foreach ( $countries as $key => $val ) { ?>
												<option value="<?php echo $key; ?>" <# if ( inArray('<?php echo $key; ?>', data.list.selected_countries ) ) { #>selected="selected"<# } #>><?php echo $val; ?></option> <?php
											}
										}
									?></select>
									<?php
									if ( empty( $countries ) ) {
										echo "<p><b>" . esc_html( "You need to setup shipping locations in WooCommerce settings", 'country-base-restrictions-pro-addon')." <a href='admin.php?page=wc-settings'> " . esc_html( "HERE", 'country-base-restrictions-pro-addon' ) . "</a> " . esc_html( "before you can choose country restrictions", 'country-base-restrictions-pro-addon' ) . "</b></p>";
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</script>
	</div>
</section>
