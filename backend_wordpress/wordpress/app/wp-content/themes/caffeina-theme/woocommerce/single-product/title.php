<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce\Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Brand
$brands = get_the_terms( get_the_ID(), 'lb-brand' );
$brand_logo = get_field('lb_brand_logo', $brands[0]);

// Benefits
$context['benefits'] = [];
if( have_rows('lb_product_benefits') ) {
    while( have_rows('lb_product_benefits') ) : the_row();
        $context['benefits'][] = get_sub_field('lb_product_benefit_item');
    endwhile;
}

$context['brand'] = $brands[0];
$context['brand_logo'] = $brand_logo;

Timber::render('@PathViews/woo/single-product/title.twig', $context);
