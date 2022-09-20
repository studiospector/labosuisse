<?php global $fzpcr; ?>
<section id="cbr_content1" class="cbr_tab_section">
	<div class="cbr_tab_inner_container">
		<form method="post" id="cbr_setting_tab_form">
						<table class="form-table heading-table" style="border-bottom:1px solid #ccc;">
			<tbody>
				<tr valign="top">
					<td>
						<h3 style=""><?php echo esc_html__( 'General Setting', 'Woo-cor' ); ?></h3>
					</td>
				</tr>
			 </tbody>
			</table>
			<table class="form-table heading-table" style="border-bottom:1px solid #ccc;">
			<tbody>
				<tr valign="top">
					<td>
						<h3 style=""><?php echo esc_html__( 'Catalog Visibility', 'Woo-cor' ); ?></h3>
					</td>
				</tr>
			 </tbody>
			</table>
			<div class="main-panel hide-child-panel">
				<table class="form-table catelog_visibility">
					<tbody>
						<tr valign="top">
							<th>
								<label><input name="product_visibility" value="hide_completely" type="radio" class="product_visibility" checked/> <?php echo esc_html__( 'Hide completely', 'Woo-cor' ); ?></label>
							</th>
						</tr>
					</tbody>
				</table>
				<div class="inside">
					<p class="desc"><?php echo esc_html__( 'This option will completely hide restricted products and product variations from your store (including a direct link)', 'Woo-cor' ); ?></p>
					
				</div>
			</div>
			<div class="main-panel hide-child-panel">
				<table class="form-table catelog_visibility">
					<tbody>
						<tr valign="top">
							<th>
								<label><input name="product_visibility" value="hide_catalog_visibility" type="radio" class="product_visibility" 
								<?php
								if ( get_option( 'product_visibility' ) == 'hide_catalog_visibility' ) {
									echo 'checked'; }
								?>
									 /> <?php echo esc_html__( 'Hide catalog visibility', 'Woo-cor' ); ?></label>
							</th>

						</tr>
					</tbody>
				</table>
				<div class="inside">
					<p class="desc"><?php echo esc_html__( 'This option will hide restricted products and product variations from your shop and search results, however these products will still be accessible and purchasable via direct link.', 'Woo-cor' ); ?></p>
					
				</div>
			</div>
			<div class="main-panel hide-child-panel" style="box-shadow:0 2px #ccc;">
				<table class="form-table catelog_visibility">
					<tbody>
						<tr valign="top">
							<th>
								<label><input name="product_visibility" value="show_catalog_visibility" type="radio" class="product_visibility" 
								<?php
								if ( get_option( 'product_visibility' ) == 'show_catalog_visibility' ) {
									echo 'checked'; }
								?>
									 /> <?php echo esc_html__( 'Catalog Visible', 'Woo-cor' ); ?></label>
							</th>
						</tr>
					</tbody>
				</table>
				<div class="inside">
					<p class="desc"><?php echo esc_html__( 'This option will display your products and product variations on the shop but not purchasable.', 'Woo-cor' ); ?></p>
					
				</div>
			</div>
			<div class="submit cbr-btn">
				<button name="save" class="cbr-save button-primary woocommerce-save-button" type="submit" value="Save changes">Save</button>
				<div class="spinner workflow_spinner" style="float:none"></div>
				<div class="success_msg workflow_success" style="display:none;"><?php echo esc_html__('Settings saved successfully.', 'Woo-cor' ); ?></div>
				<div class="error_msg workflow_error" style="display:none;"></div>
				<div class="error_msg invalid_license" style="display:none;"></div>
				<?php wp_nonce_field( 'cbr_setting_form_action', 'cbr_setting_form_nonce_field' ); ?>
				<input type="hidden" name="action" value="cbr_setting_form_update">
			</div>
		</form>	
	</div>
	
	 
	</div>
</section>
