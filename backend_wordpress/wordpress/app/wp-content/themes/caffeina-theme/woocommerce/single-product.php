<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$context = Timber::context();

$context['post'] = Timber::get_post();
$product = wc_get_product($context['post']->ID);
$context['product'] = $product;

// Get related products
$related_limit = wc_get_loop_prop('columns');
$related_ids = wc_get_related_products($context['post']->id, $related_limit);
$context['related_products'] = Timber::get_posts($related_ids);

Timber::render('@PathViews/woo/single-product.twig', $context);
