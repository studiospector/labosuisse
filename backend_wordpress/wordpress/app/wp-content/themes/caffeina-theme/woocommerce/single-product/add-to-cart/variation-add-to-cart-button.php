<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$lang = lb_get_current_lang();
$labo_in_the_world_link = get_page_link(get_field('lb_labo_in_the_world_page', 'option'));

?>
<div class="woocommerce-variation-add-to-cart variations_button">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<?php
	do_action( 'woocommerce_before_add_to_cart_quantity' );

	woocommerce_quantity_input(
		array(
            'product_name' => '',
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
		)
	);

	do_action( 'woocommerce_after_add_to_cart_quantity' );

    echo '<div class="lb-button-group">';
        Timber::render('@PathViews/components/button.twig', [
            'title' => __('Punti vendita', 'labo-suisse-theme'),
            'url' => $lang != 'it' ? $labo_in_the_world_link : get_post_type_archive_link('lb-store'),
            'variants' => ['tertiary'],
        ]);

        Timber::render('@PathViews/components/button.twig', [
            'title' => esc_html( $product->single_add_to_cart_text() ),
            'class' => 'single_add_to_cart_button alt',
            'type' => 'submit',
            'variants' => ['primary'],
        ]);
    echo '</div>';
	?>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>
