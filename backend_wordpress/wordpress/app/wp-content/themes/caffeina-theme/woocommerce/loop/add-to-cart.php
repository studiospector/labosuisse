<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

// echo apply_filters(
// 	'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
// 	sprintf(
// 		'<a href="%s" data-quantity="%s" class="button button-quaternary %s" %s><span class="button__label">%s</span><span class="lb-icon lb-icon-arrow-right"><svg aria-label="arrow-right" xmlns="http://www.w3.org/2000/svg"><use xlink:href="#arrow-right"></use></svg></span></a>',
// 		esc_url( $product->add_to_cart_url() ),
// 		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
// 		esc_attr( isset( $args['class'] ) ? $args['class'] : '' ),
// 		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
//         __('Dettagli', 'labo-suisse-theme')
// 		// esc_html( $product->add_to_cart_text() )
// 	),
// 	$product,
// 	$args
// );

Timber::render('@PathViews/components/button.twig', [
    'url' => null,
    'title' => __('Dettagli', 'labo-suisse-theme'),
    'variants' => ['quaternary']
]);
