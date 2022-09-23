<h1 class="tab_main_heading"><?php esc_html_e( 'License', 'country-base-restrictions-pro-addon' ); ?></h1>
<div class="license_connection_section cbr-btn">

<?php 
$zorem_license_connected = get_option( 'zorem_license_connected', 0 );
$zorem_license_email = get_option( 'zorem_license_email', '' );
$current_url = esc_url( admin_url( '/admin.php?page=woocommerce-product-country-base-restrictions&tab=license' ) ); 

if ( $this->get_license_status() ) {
	?>
	<h3 class="licnese-inner-heading"><?php esc_html_e( 'Status:', 'country-base-restrictions-pro-addon' ); ?><span class="success">Active</span></h3>
	<p><?php esc_html_e('Want to deactivate the license for any reason?', 'country-base-restrictions-pro-addon'); ?></p>
	<form method="post" id="cbr-license-form" class="addons_inner_container cbr-license-form" action="" enctype="multipart/form-data">
		<input type="hidden" name="license_key" id="license_key" value="<?php esc_html_e( $this->get_license_key() ); ?>">
		<input type="hidden" id="cbr-action" name="action" class="license_action" value="<?php echo $this->get_license_status() ? esc_html($this->get_item_code() . '_license_deactivate') : esc_html($this->get_item_code() . '_license_activate'); ?>">
		<button name="save" class="button-primary btn_green2" type="submit" value="Deactivate"><?php esc_html_e( 'Deactivate', 'country-base-restrictions-pro-addon' ); ?></button>
	</form>
	<?php
} else if ( 1 == $zorem_license_connected && '' != $zorem_license_email ) {
	?>
	<h3 class="licnese-inner-heading"><?php esc_html_e( 'Activate License', 'country-base-restrictions-pro-addon' ); ?></h3>
	<p><?php esc_html_e('Activate your license to receive automatic updates and premium support', 'country-base-restrictions-pro-addon'); ?></p>
	<a href="https://www.zorem.com/my-account/license-activation/?product_id=<?php echo $this->get_product_id(); ?>&redirect_url=<?php echo urlencode( $current_url ); ?>" class="button-primary btn_ast2"><?php esc_html_e( 'Activate License', 'country-base-restrictions-pro-addon' ); ?></a>
	<?php
} else {	
	?>
	<h3 class="licnese-inner-heading"><?php esc_html_e( 'Activate License', 'country-base-restrictions-pro-addon' ); ?></h3>
	<p><?php esc_html_e('Activate your license to receive automatic updates and premium support.', 'country-base-restrictions-pro-addon'); ?></p>
	<a href="https://www.zorem.com/my-account/license-activation/?product_id=<?php echo $this->get_product_id(); ?>&redirect_url=<?php echo urlencode( $current_url ); ?>" class="button-primary btn_ast2"><?php esc_html_e( 'Connect & Activate', 'country-base-restrictions-pro-addon' ); ?></a>
	<?php
}
?>
</div>
