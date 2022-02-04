<?php

/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.1
 */

defined('ABSPATH') || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if (!function_exists('wc_get_gallery_image_html')) {
    return;
}

global $product;

/*
$post_thumbnail_id = $product->get_image_id();

if ($post_thumbnail_id) {
    $html = lb_wc_get_gallery_image_html($post_thumbnail_id, true);
} else {
    $html  = '<div class="lb-product-gallery__image--placeholder">';
    $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src('woocommerce_single')), esc_html__('Awaiting product image', 'woocommerce'));
    $html .= '</div>';
}


?>
<div class="lb-product-gallery js-lb-product-gallery">
    <div class="swiper lb-product-gallery-slider">
        <div class="swiper-wrapper">
            <?php
            echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
            do_action('woocommerce_product_thumbnails');
            ?>
        </div>
        <div class="swiper-pagination"></div>
        <!-- <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div> -->
    </div>
    <div thumbsSlider="" class="swiper lb-product-gallery-thumb">
        <div class="swiper-wrapper">
            <?php
            echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
            do_action('woocommerce_product_thumbnails');
            ?>
        </div>
    </div>
</div>
<?php
*/ ?>
<div class="lb-product-gallery js-lb-product-gallery">

    <div class="lb-product-gallery__desktop">
        <!-- Featured image -->
        <div class="lb-product-gallery__featured-img">
            <?php $featured_img_attributes = wp_get_attachment_image_src( $product->get_image_id(), 'full' ); ?>
            <a href="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" data-pswp-width="<?php echo $featured_img_attributes[1]; ?>" data-pswp-height="<?php echo $featured_img_attributes[2]; ?>" target="_blank">
                <?php echo wp_get_attachment_image($product->get_image_id(), 'full'); ?>
            </a>
        </div>
        <!-- Gallery images -->
        <div class="lb-product-gallery__thumbs">
            <div class="row">
                <?php
                    $attachment_ids = $product->get_gallery_image_ids();
                    foreach( $attachment_ids as $attachment_id ) {
                        $image_url = wp_get_attachment_url( $attachment_id );
                        $image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' );
                        ?>
                        <div class="col-6">
                            <a href="<?php echo $image_url; ?>" data-pswp-width="<?php echo $image_attributes[1]; ?>" data-pswp-height="<?php echo $image_attributes[2]; ?>" target="_blank">
                                <?php echo wp_get_attachment_image($attachment_id); ?>
                            </a>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>

    <div class="lb-product-gallery__mobile">
        <div class="lb-product-gallery__mobile__slider swiper">
            <div class="swiper-wrapper">
                <!-- Featured image -->
                <?php $featured_img_attributes = wp_get_attachment_image_src( $product->get_image_id(), 'full' ); ?>
                <div class="swiper-slide">
                    <a href="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" data-pswp-width="<?php echo $featured_img_attributes[1]; ?>" data-pswp-height="<?php echo $featured_img_attributes[2]; ?>" target="_blank">
                        <?php echo wp_get_attachment_image($product->get_image_id(), 'full'); ?>
                    </a>
                </div>
                <!-- Gallery images -->
                <?php
                    $attachment_ids = $product->get_gallery_image_ids();
                    foreach( $attachment_ids as $attachment_id ) {
                        $image_url = wp_get_attachment_url( $attachment_id );
                        $image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' );
                        ?>
                        <div class="swiper-slide">
                            <a href="<?php echo $image_url; ?>" data-pswp-width="<?php echo $image_attributes[1]; ?>" data-pswp-height="<?php echo $image_attributes[2]; ?>" target="_blank">
                                <?php echo wp_get_attachment_image($attachment_id); ?>
                            </a>
                        </div>
                        <?php
                    }
                ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    
</div>
