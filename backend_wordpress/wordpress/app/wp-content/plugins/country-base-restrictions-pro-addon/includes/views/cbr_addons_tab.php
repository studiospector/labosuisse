<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * html code for tools tab
*/
$pro_plugins = array(
	5 => array(
		'title' => 'Advanced Shipment Tracking Pro',
		'description' => 'AST PRO provides powerful features to easily add tracking info to WooCommerce orders, automate the fulfillment workflows and keep your customers happy and informed. AST allows you to easily add tracking and fulfill your orders straight from the Orders page, while editing orders, and allows customers to view the tracking i from the View Order page.',
		'url' => 'https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/?utm_source=wp-admin&utm_medium=AST&utm_campaign=add-ons',
		'image' => 'ast-icon.png',
		'height' => '45px',
		'file' => 'ast-pro/ast-pro.php'
	),			
	0 => array(
		'title' => 'TrackShip for WooCommerce',
		'description' => 'Take control of your post-shipping workflows, reduce time spent on customer service and provide a superior post-purchase experience to your customers.Beyond automatic shipment tracking, TrackShip brings a branded tracking experience into your store, integrates into your workflow, and takes care of all the touch points with your customers after shipping.',
		'url' => 'https://wordpress.org/plugins/trackship-for-woocommerce/?utm_source=wp-admin&utm_medium=ts4wc&utm_campaign=add-ons',
		'image' => 'trackship-logo.png',
		'height' => '45px',
		'file' => 'trackship-for-woocommerce/trackship-for-woocommerce.php'
	),
	1 => array(
		'title' => 'SMS for WooCommerce',
		'description' => 'Keep your customers informed by sending them automated SMS text messages with order & delivery updates. You can send SMS notifications to customers when the order status is updated or when the shipment is out for delivery and more…',
		'url' => 'https://www.zorem.com/product/sms-for-woocommerce/?utm_source=wp-admin&utm_medium=SMSWOO&utm_campaign=add-ons',
		'image' => 'smswoo-icon.png',
		'height' => '45px',
		'file' => 'sms-for-woocommerce/sms-for-woocommerce.php'
	),
	3 => array(
		'title' => 'Advanced Local Pickup',
		'description' => 'The Advanced Local Pickup (ALP) helps you manage the local pickup orders workflow more conveniently by extending the WooCommerce Local Pickup shipping method. The Pro you set up multiple pickup locations, split the business hours, apply discounts by pickup location, display local pickup message on the products pages, allow customers to choose pickup location per product, force products to be local pickup only and more…',
		'url' => 'https://www.zorem.com/product/advanced-local-pickup-pro/?utm_source=wp-admin&utm_medium=ALPPRO&utm_campaign=add-ons',
		'image' => 'alp-icon.png',
		'height' => '45px',
		'file' => 'advanced-local-pickup-pro/advanced-local-pickup-pro.php'
	),
	4 => array(
		'title' => 'Advanced Order Status Manager',
		'description' => 'The Advanced Order Status Manager allows store owners to manage the WooCommerce orders statuses, create, edit, and delete custom Custom Order Statuses and integrate them into the WooCommerce orders flow.',
		'url' => 'https://www.zorem.com/products/advanced-order-status-manager/?utm_source=wp-admin&utm_medium=OSM&utm_campaign=add-ons',
		'image' => 'osm-icon.png',
		'height' => '45px',
		'file' => 'advanced-order-status-manager/advanced-order-status-manager.php'
	),
	2 => array(
		'title' => 'Sales Report Email',
		'description' => 'The Sales Report Email Pro will help know how well your store is performing and how your products are selling by sending you a daily, weekly, or monthly sales report by email, directly from your WooCommerce store.',
		'url' => 'https://www.zorem.com/product/sales-report-email-pro/?utm_source=wp-admin&utm_medium=SRE&utm_campaign=add-ons',
		'image' => 'sre-icon.png',
		'height' => '45px',
		'file' => 'sales-report-email-pro-addon/sales-report-email-pro-addon.php'
	),
);
?>
<section id="cbr_content4" class="cbr_tab_section">
	<div class="d_table addons_page_dtable" style="">
		<!--<h2 class="tab_main_heading"><?php //esc_html_e( 'License', 'country-base-restrictions-pro-addon' ); ?></h2>-->
		<section id="content_tab_license" class="inner_tab_section">
			<?php do_action('cbr_addon_license_form'); ?>
		</section>
	</div>
	<section id="content_tab_addons" class="inner_tab_section">
		<div class="section-content">
			<div class="plugins_section free_plugin_section">
				<?php foreach ($pro_plugins as $Plugin) { ?>
					<div class="single_plugin">
								<div class="free_plugin_inner">
									<div class="plugin_image">
										<img src="<?php echo esc_url(cbr_pro_addon()->plugin_dir_url() . 'assets/images/' . $Plugin['image']); ?>" height="<?php echo esc_html($Plugin['height']); ?>">
										<h3 class="plugin_title"><?php echo esc_html($Plugin['title']); ?></h3>
									</div>
									<div class="plugin_description cbr-btn">

										<p><?php echo esc_html($Plugin['description']); ?></p>
										<?php if ( is_plugin_active( $Plugin['file'] ) ) { ?>
											<button type="button" class="button button-disabled" disabled="disabled">Installed</button>
										<?php } else { ?>
											<a href="<?php echo esc_url($Plugin['url']); ?>" class="button install-now button-primary" target="blank">more info</a>
										<?php } ?>								
									</div>
								</div>	
							</div>	
				<?php } ?>						
			</div>
		</div>		
	</section>	
</section>		
