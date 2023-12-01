<?php

/**
 * Re add action of WooCommerce hooks because of ajax call
 */
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);



/**
 * WC Support
 */
add_action('init', 'lb_wc_support');
function lb_wc_support()
{
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
 * Change the default state on the checkout page
 */
add_filter('default_checkout_billing_state', '__return_null');



/**
 * Custom select for product variations
 */
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'lb_set_custom_data_attribute_product_variations', 10, 2);
function lb_set_custom_data_attribute_product_variations($html, $args)
{
    $label = ucfirst(str_replace("pa_", "", $args['attribute']));

    $html = str_replace("id=\"{$args['attribute']}\"", "id=\"{$args['attribute']}\" " . "data-variant=\"tertiary\" data-label=\"$label\" data-wc-variations-support=\"true\"", $html);

    $regex = '/\[\[\w\d{1,3}\]\]/';
    $matches = [];
    preg_match_all($regex, $html, $matches);
    foreach ($matches[0] as $match) {
        $match = str_replace(['[',']'],'', $match);
        $hexadecimal = get_field('lb_product_color_taxonomy_hexadecimal', get_term_by('name', $match, 'pa_colore'));
        $colorName = get_field('lb_product_color_taxonomy_color_name', get_term_by('name', $match, 'pa_colore'));
        $slug = strtolower($match);
        $search =  "<option value=\"{$slug}\" >[[{$match}]]</option>";
        $replace = "<option value=\"{$slug}\" data-option-color=\"{$hexadecimal}\">{$colorName}</option>";

        $html = str_replace($search, $replace, $html);
    }
    return $html;
}

add_action('woocommerce_variation_option_name',function ($option, $options, $attribute, $product) {
    if ($attribute == 'pa_colore') {
        $option = "[[{$option}]]";
    }
    return $option;
},1000,4);

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
add_filter('woocommerce_get_price_html', 'lb_product_price_html', 10, 2);
function lb_product_price_html($price, $product)
{
    if (is_admin()) {
        return $price;
    }

    if (!$product->is_purchasable()) {
        return null;
    }

    if ($product->is_type('variable')) {
        $regular_price = $product->get_variation_regular_price('min');
        $sale_price = $product->get_variation_sale_price('min');

        $wc_regular_price = $regular_price ? wc_price($regular_price) : null;
        $wc_sale_price = $sale_price && $sale_price != $regular_price ? wc_price($sale_price) : null;

        $custom_price = sprintf(
            __('%1$s %2$sa partire da%3$s%4$s %5$s %6$s', 'labo-suisse-theme'),
            '<span class="lb-wc-price">',
            '<span class="lb-wc-price__desc">',
            '</span>',
            $wc_sale_price ? "<del>$wc_regular_price</del>" : '',
            !$wc_sale_price ? "<ins>$wc_regular_price</ins>" : "<ins>$wc_sale_price</ins>",
            '</span>',
        );
    } else {
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();

        $wc_regular_price = $regular_price ? wc_price($regular_price) : null;
        $wc_sale_price = $sale_price && $sale_price != $regular_price ? wc_price($sale_price) : null;

        $custom_price = sprintf(
            '%1$s %2$s %3$s %4$s',
            '<span class="lb-wc-price">',
            $wc_sale_price ? "<del>$wc_regular_price</del>" : '',
            !$wc_sale_price ? "<ins>$wc_regular_price</ins>" : "<ins>$wc_sale_price</ins>",
            '</span>',
        );
    }

    return $custom_price;
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
        } else if (in_array($args['type'], ['country', 'state'])) {
            $args['label_class'] = ['lb-wc-label'];
        } else if (in_array($args['type'], ['textarea'])) {
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
add_filter('woocommerce_add_to_cart_fragments', 'lb_wc_header_add_to_cart_fragment');
function lb_wc_header_add_to_cart_fragment($fragments)
{
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

/**
 * Defer scripts JS
 */
// function lb_defer_parsing_of_js($url)
// {
//     if (strpos($url, 'jquery.min.js'))
//         return str_replace(' src', ' defer src', $url);

//     return $url;
// }
// add_filter('script_loader_tag', 'lb_defer_parsing_of_js', 10);


/**
 * Woo discount rules
 */
add_filter('woocommerce_get_item_data', function ($item_data, $cart_item) {
    if (isset($cart_item['wdr_free_product']) and $cart_item['wdr_free_product'] == 'Free') {
        $item_data = [];
    }

    return $item_data;
}, 100, 2);

add_filter('woocommerce_cart_item_quantity', function ($product_quantity, $cart_item_key, $cart_item) {
    if (isset($cart_item['wdr_free_product']) and $cart_item['wdr_free_product'] == 'Free') {
        return null;
    }

    return $product_quantity;
}, 100, 3);

add_filter('woocommerce_cart_item_subtotal', 'getCartItemQuantity', 100, 3);
add_filter('woocommerce_widget_cart_item_quantity', 'getCartItemQuantity', 100, 3);

function getCartItemQuantity($product_subtotal, $cart_item, $cart_item_key)
{
    if (isset($cart_item['wdr_free_product']) and $cart_item['wdr_free_product'] == 'Free') {
        return sprintf(
            '<span class="">%s</span>',
            __('Omaggio', 'labo-suisse-theme')
        );
    }

    return $product_subtotal;
}

add_filter('woocommerce_cart_contents_count', function () {
    $cart_contents_count = 0;

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if (!isset($cart_item['wdr_free_product']) or $cart_item['wdr_free_product'] != 'Free') {
            $cart_contents_count++;
        }
    }

    return $cart_contents_count;
});

add_filter('woocommerce_adjust_non_base_location_prices', '__return_false');

add_filter('woocommerce_add_to_cart', function() {
    $cart = WC()->cart->get_cart();

    $freeProductIds = getFreeProductIds();

    foreach ($cart as $key => $cart_item) {
        if (in_array($cart_item['product_id'], $freeProductIds) and !isset($cart_item['wdr_free_product'])) {
            WC()->cart->remove_cart_item($key);
        }
    }
},100);

/**
 * Enforce Password Strength
 */
add_filter('woocommerce_min_password_strength', 'lb_wc_change_password_strength', 30);
function lb_wc_change_password_strength()
{
    return intval(2);
}

/**
 * Set extra Product Schema
 */
add_filter('woocommerce_structured_data_product', 'lb_set_extra_schema_product', 20, 2);
function lb_set_extra_schema_product($schema, $product)
{
    $brands = get_the_terms($product->get_id(), 'lb-brand');

    if ($brands) {
        $schema['brand'] = $brands[0]->name;
    }

    return $schema;
}

/**
 * Set extra Product data in GTM items
 */
add_filter('gtm_ecommerce_woo_item', function ($item, $product) {
    // Add Brand
    $brands = (get_class($product) === 'WC_Product_Variation') ? get_the_terms($product->get_parent_id(), 'lb-brand') : get_the_terms($product->get_id(), 'lb-brand');
    if ($brands) {
        $item->setItemBrand($brands[0]->name);
    }

    // Add Product cats
    $productCats = (get_class($product) === 'WC_Product_Variation') ? get_the_terms($product->get_parent_id(), 'product_cat') : get_the_terms($product->get_id(), 'product_cat');
    if (is_array($productCats)) {
        $categories = array_map(
            function ($category) {
                return $category->name;
            },
            $productCats
        );
        $item->setItemCategories($categories);
    }

    return $item;
}, 999, 2);

/**
 * Allowed Countries
 */
add_filter('woocommerce_countries_allowed_countries', 'lb_filter_wc_allowed_countries', 10, 1);
function lb_filter_wc_allowed_countries($countries)
{
    // Cart or checkout page
    if (is_cart() || is_checkout()) {
        // BE, IT, FR, IE, ES, NL, DE
        $accepted_en = array('BE' => null, 'FR' => null, 'IE' => null, 'ES' => null, 'NL' => null, 'DE' => null);
        $accepted_it = array('IT' => null);

        $accepted = lb_get_current_lang() == 'it' ? $accepted_it : $accepted_en;

        $countries = array_intersect_key($countries, $accepted);
    }

    return $countries;
}

/**
 * Filters the list of attachment image attributes
 */
add_filter('wp_get_attachment_image_attributes', 'lb_filter_wp_get_attachment_image_attributes', 10, 3);
function lb_filter_wp_get_attachment_image_attributes($attr, $attachment, $size)
{
    if ((is_product() || is_product_category() || is_tax('lb-brand') && !is_admin())) {
        $attr['class'] .= ' lazyload';

        if (!empty($attr['src'])) {
            $attr['data-src'] = $attr['src'];
            unset($attr['src']);
        }

        if (!empty($attr['srcset'])) {
            $attr['data-srcset'] = $attr['srcset'];
            unset($attr['srcset']);
        }

        if (!empty($attr['loading'])) {
            unset($attr['loading']);
        }

        if (!empty($attr['decoding'])) {
            unset($attr['decoding']);
        }
    }

    return $attr;
}

add_action('woocommerce_new_order', function($orderId) {
    global $sitepress;
    // Current site lang
    $currentLang = $sitepress->get_current_language();

    $currentUser = wp_get_current_user();

    if(property_exists($currentUser->data,'ID')) {
        update_user_meta($currentUser->data->ID, 'lb_user_language', $currentLang);
    }
});

//
//add_action('woocommerce_dropdown_variation_attribute_options_args', function($args) {
//
//    if($args['attribute'] == 'pa_colore') {
//        $colors = [];
//
//        foreach ($args['options'] as $option) {
//            $colors[] = str_replace('#','',get_field('esadecimale', get_term_by('name', $option, 'pa_colore')));
//        }
//
//        print_r($colors);
////        $args['options'] = $colors;
//    }
//
//    print_r($args['options']);
//    return $args;
//});

