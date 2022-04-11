<?php

/**
 * WC Support
 */
add_action( 'init', 'lb_wc_support' );	 	 
function lb_wc_support() {	 	 
   remove_theme_support( 'wc-product-gallery-lightbox' );	 	 
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

    $html = str_replace("id=\"{$args['attribute']}\"", "id=\"{$args['attribute']}\" " . "data-variant=\"tertiary\" data-label=\"$label\"", $html);

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



// add_filter('woocommerce_single_product_image_thumbnail_html', 'remove_featured_image', 10, 2);
// function remove_featured_image($html, $attachment_id)
// {
//     global $post, $product;

//     $featured_image = get_post_thumbnail_id($post->ID);

//     if ($attachment_id == $featured_image)
//         return;

//     return $html;
// }




// function filter_woocommerce_product_gallery_attachment_ids( $items ) {
//     global $post;
//     $featured_image = get_post_thumbnail_id($post->ID);

//     foreach ($items as $key => $item){
//         if ($item != $featured_image) {
//             unset($items[$key]);
//         }
//     }

//     return $items; 
// }
// add_filter( 'woocommerce_product_get_gallery_image_ids', 'filter_woocommerce_product_gallery_attachment_ids', 10, 1 );



function lb_wc_get_gallery_image_html( $attachment_id, $main_image = false ) {
	$flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
	$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
	$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
	$image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
	$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
	$thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
	$full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
	$alt_text          = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
	$image             = wp_get_attachment_image(
		$attachment_id,
		$image_size,
		false,
		apply_filters(
			'woocommerce_gallery_image_html_attachment_image_params',
			array(
				'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
				'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
				'data-src'                => esc_url( $full_src[0] ),
				'data-large_image'        => esc_url( $full_src[0] ),
				'data-large_image_width'  => esc_attr( $full_src[1] ),
				'data-large_image_height' => esc_attr( $full_src[2] ),
				'class'                   => esc_attr( $main_image ? 'wp-post-image' : '' ),
			),
			$attachment_id,
			$image_size,
			$main_image
		)
	);

    // if ($main_image) {
    //     $html = '<a href="'. $full_src[0] .'" data-pswp-width="'. $gallery_thumbnail['width'] .'" data-pswp-height="'. $gallery_thumbnail['height'] .'" target="_blank" class="lb-product-gallery__image swiper-slide">' . $image . '</a>';
    // } else {
    //     $html = '<div class="lb-product-gallery__image swiper-slide">' . $image . '</div>';
    // }

	return '<div data-pswp-src="'. $full_src[0] .'" data-pswp-width="'. $gallery_thumbnail['width'] .'" data-pswp-height="'. $gallery_thumbnail['height'] .'" class="lb-product-gallery__image swiper-slide">' . $image . '</div>';;
    
}