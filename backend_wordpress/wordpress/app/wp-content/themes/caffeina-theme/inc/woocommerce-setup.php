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



/**
 * Set base options for input quantity
 */
add_filter('woocommerce_get_stock_html', function ($html, $product) {
    return '';
}, 10, 2);

add_filter('woocommerce_quantity_input_classes', function ($value, $product) {
    return ['js-custom-input', 'qty'];
}, 10, 2);



/**
 * Change Price Range for Variable Products
 */
add_filter('woocommerce_variable_sale_price_html', 'lb_variable_product_price', 10, 2);
add_filter('woocommerce_variable_price_html', 'lb_variable_product_price', 10, 2);
function lb_variable_product_price($v_price, $v_product)
{
    $classes = !is_product() ? 'lb-product-card__price__desc' : 'lb-price-label infobox__paragraph--small';
    
    $min_price = $v_product->get_variation_price('min', true);

    $price_html = sprintf(
        __('%1$sa partire da%2$s %3$s', 'labo-suisse-theme'),
        '<span class="'. $classes .'">',
        '</span>',
        wc_price($min_price),
    );

    return $price_html;
}



/**
 * Form fields custom
 */
add_filter('woocommerce_form_field_args', 'lb_custom_form_field_args', 10, 3);
function lb_custom_form_field_args($args, $key, $value)
{
    if (!is_checkout()) {
        if (in_array($args['type'], ['text', 'number', 'email', 'tel', 'password'])) {
            $args['input_class'] = ['js-custom-input'];
            $args['placeholder'] = ($args['placeholder'] ? $args['placeholder'] : $args['label']) . ($args['required'] ? '*' : '');
    
            $args['custom_attributes'] = [
                'data-label' => ($args['label'] ? $args['label'] : $args['placeholder']) . ($args['required'] ? '*' : ''),
                'data-variant' => 'tertiary',
            ];
            $args['label'] = '';
        }
        else if (in_array($args['type'], ['country', 'state'])) {
            $args['label_class'] = ['lb-wc-label'];
        }
        else if (in_array($args['type'], ['textarea'])) {
            $args['class'] = ['custom-input', 'custom-field'];
            $args['label_class'] = ['lb-wc-label'];
        }
    }

    return $args;
}



/**
 * Remove menu link my account
 */
add_filter('woocommerce_account_menu_items', 'lb_wc_remove_account_menu_link');
function lb_wc_remove_account_menu_link($menu_links)
{
    unset($menu_links['dashboard']);
    return $menu_links;
}



/**
 * Manage columns to orders table of user account
 */
add_filter('woocommerce_my_account_my_orders_columns', 'lb_add_account_orders_column');
function lb_add_account_orders_column($columns)
{
    $columns['lb-articles-count-column'] = __('Articoli', 'labo-suisse-theme');
    $columns['order-actions'] = '&nbsp;';

    $columns = lb_move_array_element($columns, 5, 2, 'lb-articles-count-column');
    $columns = lb_move_array_element($columns, 3, 4, 'order-status');

    return $columns;
}

/**
 * Add value to custom column of articles count in orders table of user account
 */
add_action('woocommerce_my_account_my_orders_column_lb-articles-count-column', 'lb_add_account_orders_column_rows');
function lb_add_account_orders_column_rows($order)
{
    if ($order->get_item_count() == 1) {
        printf('%1$s %2$s ', $order->get_item_count(), __('articolo', 'labo-suisse-theme'));
    } else if ($order->get_item_count() > 1) {
        printf('%1$s %2$s ', $order->get_item_count(), __('articoli', 'labo-suisse-theme'));
    }
}

/**
 * Ajax Fragments
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'lb_wc_header_add_to_cart_fragment' );
function lb_wc_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;

	ob_start();
	?>
	<span class="lb-wc-cart-total-count lb-icon__counter"><?php echo $woocommerce->cart->cart_contents_count > 0 ? $woocommerce->cart->cart_contents_count : ''; ?></span>
	<?php
	$fragments['span.lb-wc-cart-total-count'] = ob_get_clean();
	return $fragments;
}

/**
 * Set max quantity purchasable foreach product
 */

// Simple products
add_filter('woocommerce_quantity_input_args', 'lb_wc_quantity_input_args', 10, 2);
function lb_wc_quantity_input_args($args, $product)
{
    // if (is_singular('product')) {
    //     $args['input_value'] = 2;
    // }
    $args['max_value'] = 5;
    $args['step'] = 1;

    return $args;
}

// Variable products
add_filter('woocommerce_available_variation', 'lb_wc_available_variation');
function lb_wc_available_variation($args)
{
    $args['max_qty'] = 5;
    $args['min_qty'] = 1;

    return $args;
}

/**
 * Add allowed tags to WC notices content
 */
add_filter('woocommerce_kses_notice_allowed_tags', 'lb_wc_kses_notice_allowed_tags');
function lb_wc_kses_notice_allowed_tags($allowed_tags)
{
    return array_merge($allowed_tags, [
        'svg' => array(
            'aria-label' => true,
            'xmlns' => true,
        ),
        'use' => [
            'xlink:href' => true,
        ]
    ]);
}
