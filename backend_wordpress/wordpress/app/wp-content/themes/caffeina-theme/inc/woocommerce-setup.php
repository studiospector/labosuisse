<?php

/**
 * Re add action of WooCommerce hooks because of ajax call
 */
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);

/**
 * WC Support
 */
add_action( 'init', 'lb_wc_support' );	 	 
function lb_wc_support() {	 	 
    add_post_type_support('product', 'page-attributes');
    remove_theme_support('wc-product-gallery-lightbox');	 	 
}



/**
 * Remove classes from body_class function
 */
add_filter('body_class', 'lb_set_wc_custom_body_classes');
function lb_set_wc_custom_body_classes($classes)
{
    $filtered_classes = array_filter(
        $classes,
        function ($val) {
            return !in_array($val, ['woocommerce']);
        }
    );

    if (is_woocommerce()) {
        array_push($filtered_classes, 'lb-is-shop');
    }

    return $filtered_classes;
}



/**
 * Custom select for product variations
 */
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'lb_set_custom_data_attribute_product_variations', 10, 2);
function lb_set_custom_data_attribute_product_variations($html, $args)
{
    $label = ucfirst(str_replace("pa_", "", $args['attribute']));

    $html = str_replace("id=\"{$args['attribute']}\"", "id=\"{$args['attribute']}\" " . "data-variant=\"tertiary\" data-label=\"$label\" data-wc-variations-support=\"true\"", $html);

    return $html;
}



/**
 * Customize WC breadcrumb
 */
add_filter('woocommerce_breadcrumb_defaults', 'lb_woocommerce_breadcrumbs');
function lb_woocommerce_breadcrumbs()
{
    return array(
        'delimiter'   => ' <span class="woocommerce-breadcrumb__separator"><svg width="7" height="8" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.675.12a.5.5 0 0 0-.055.705L4.341 4l-2.72 3.175a.5.5 0 0 0 .759.65L5.659 4 2.379.175A.5.5 0 0 0 1.676.12Z" fill="#474747"/></svg></span> ',
        'wrap_before' => '<nav class="woocommerce-breadcrumb container" itemprop="breadcrumb">',
        'wrap_after'  => '</nav>',
        'before'      => '',
        'after'       => '',
        'home'        => _x('Homepage', 'breadcrumb', 'woocommerce'),
    );
}



/**
 * Move single product price after short description
 */
add_action('woocommerce_single_product_summary', 'lb_move_single_product_price', 1);
function lb_move_single_product_price()
{
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 29);
}



/**
 * Thumbnails product image size of Single page Product Gallery
 */
add_filter('woocommerce_get_image_size_gallery_thumbnail', function ($size) {
    return array(
        'width' => 450,
        'height' => null,
        'crop' => 0,
    );
});



/**
 * Thumbnails product image size of Product cards
 */
add_filter('single_product_archive_thumbnail_size', function ($size) {
    return 'woocommerce_gallery_thumbnail';
});
