<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

global $product;

$lang = lb_get_current_lang();
$labo_in_the_world_link = get_page_link(get_field('lb_labo_in_the_world_page', 'option'));

if ( ! $product->is_purchasable() ) {
    Timber::render('@PathViews/components/button.twig', [
        'title' => __('Trova una farmacia autorizzata', 'labo-suisse-theme'),
        'url' => $lang != 'it' ? $labo_in_the_world_link : get_post_type_archive_link('lb-store'),
        'variants' => ['tertiary'],
    ]);
    
	return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
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
                'attributes' => 'name="add-to-cart" value="'. esc_attr( $product->get_id() ) .'"',
                'class' => 'single_add_to_cart_button alt ' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ),
                'type' => 'button',
                'variants' => ['primary'],
            ]);
        echo '</div>';
		?>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php else: ?>

    <?php
        Timber::render('@PathViews/components/button.twig', [
            'title' => __('Trova una farmacia autorizzata', 'labo-suisse-theme'),
            'url' => $lang != 'it' ? $labo_in_the_world_link : get_post_type_archive_link('lb-store'),
            'variants' => ['tertiary'],
        ]);
    ?>

<?php endif; ?>
