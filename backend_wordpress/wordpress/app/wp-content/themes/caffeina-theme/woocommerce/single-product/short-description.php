<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

if ( ! $short_description ) {
	return;
}

?>
<div class="woocommerce-product-details__short-description single-product-details__summary__short-description">
	<?php echo $short_description; // WPCS: XSS ok. ?>
    <button type="button" class="button button-quaternary js-scroll-to" data-scroll-to=".block-offset-navs">
        <span class="button__label"><?php echo __('Più informazioni', 'labo-suisse-theme') ?></span>
        <span class="lb-icon lb-icon-arrow-right">
            <svg aria-label="arrow-right" xmlns="http://www.w3.org/2000/svg">
            <use xlink:href="#arrow-right"></use>
            </svg>
        </span>
    </button>
</div>
