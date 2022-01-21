<?php

/**
 * Constants
 */
if (!defined('LB_DIR_PATH')) {
	define('LB_DIR_PATH', untrailingslashit(get_template_directory()));
}

if (!defined('LB_DIR_URI')) {
	define('LB_DIR_URI', untrailingslashit(get_template_directory_uri()));
}

if (!defined('LB_BUILD_URI')) {
	define('LB_BUILD_URI', untrailingslashit(get_template_directory_uri()) . '/static');
}

if (!defined('LB_BUILD_PATH')) {
	define('LB_BUILD_PATH', untrailingslashit(get_template_directory()) . '/static');
}

if (!defined('LB_BUILD_JS_URI')) {
	define('LB_BUILD_JS_URI', untrailingslashit(get_template_directory_uri()) . '/static/js');
}

if (!defined('LB_BUILD_JS_DIR_PATH')) {
	define('LB_BUILD_JS_DIR_PATH', untrailingslashit(get_template_directory()) . '/static/js');
}

if (!defined('LB_BUILD_IMG_URI')) {
	define('LB_BUILD_IMG_URI', untrailingslashit(get_template_directory_uri()) . '/static/src/img');
}

if (!defined('LB_BUILD_CSS_URI')) {
	define('LB_BUILD_CSS_URI', untrailingslashit(get_template_directory_uri()) . '/static/css');
}

if (!defined('LB_BUILD_CSS_DIR_PATH')) {
	define('LB_BUILD_CSS_DIR_PATH', untrailingslashit(get_template_directory()) . '/static/css');
}

if (!defined('LB_BUILD_LIB_URI')) {
	define('LB_BUILD_LIB_URI', untrailingslashit(get_template_directory_uri()) . '/static/library');
}



/**
 * Includes
 */
include __DIR__ . '/inc/theme-setup.php';
include __DIR__ . '/inc/theme-assets.php';



/**
 * ACF Blocks autoload
 */

 $files = glob(__DIR__ . '/acf-config/blocks/*');
 #$files = list_files(__DIR__ . '/acf-config/blocks');
 foreach ( $files as $file ) {
    include_once $file;
 }
// include __DIR__ . '/acf-config/blocks/acf-block-carousel-hero.php';
// include __DIR__ . '/acf-config/blocks/acf-block-launch-two-images.php';
// include __DIR__ . '/acf-config/blocks/acf-block-hero.php';
// include __DIR__ . '/acf-config/blocks/acf-block-love-labo.php';





/**
 * Registers the lb-brand taxonomy
 */
function lb_brand_tax_init() {

	$args = array(
		'labels' => array(
            'name'              => _x( 'Brands', 'taxonomy general name', 'lb-brand-tax' ),
            'singular_name'     => _x( 'Brand', 'taxonomy singular name', 'lb-brand-tax' ),
            'search_items'      => __( 'Search Brands', 'lb-brand-tax' ),
            'all_items'         => __( 'All Brands', 'lb-brand-tax' ),
            'parent_item'       => __( 'Parent Brand', 'lb-brand-tax' ),
            'parent_item_colon' => __( 'Parent Brand:', 'lb-brand-tax' ),
            'edit_item'         => __( 'Edit Brand', 'lb-brand-tax' ),
            'update_item'       => __( 'Update Brand', 'lb-brand-tax' ),
            'add_new_item'      => __( 'Add New Brand', 'lb-brand-tax' ),
            'new_item_name'     => __( 'New Brand Name', 'lb-brand-tax' ),
            'menu_name'         => __( 'Brand', 'lb-brand-tax' ),
        ),
		'description' => __( '', 'lb-brand-tax' ),
		'hierarchical' => true,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud' => true,
		'show_in_quick_edit' => true,
		'show_admin_column' => false,
		'show_in_rest' => true,
	);
	register_taxonomy( 'lb-brand', array('product'), $args );
}
add_action( 'init', 'lb_brand_tax_init' );



/**
 * Parse CF7 shortcode tags to implement custom HTML data attributes on fields
 */
add_filter( 'wpcf7_form_tag', function ( $tag ) {
    $datas = [];
    foreach ( (array)$tag['options'] as $option ) {
        if ( strpos( $option, 'data-' ) === 0 ) {
            $option = explode( ':', $option, 2 );
            $data_attribute = $option[0];
            $data_value = str_replace('|', ' ', $option[1]);
            $datas[$data_attribute] = apply_filters('wpcf7_option_value', $data_value, $data_attribute);
        }
    }
    if ( ! empty( $datas ) ) {
        $id = $name = ($tag['basetype'] == 'select') ? "{$tag['name']}[]" : $tag['name'];
        add_filter( 'wpcf7_form_elements', function ($content) use ($name, $id, $datas) {
            return str_replace($id, $name, str_replace("name=\"$id\"", "name=\"$name\" ". wpcf7_format_atts($datas), $content));
        });
    }
    return $tag;
} );
