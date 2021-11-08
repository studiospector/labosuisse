<?php

if (!defined('CF_DIR_PATH')) {
	define('CF_DIR_PATH', untrailingslashit(get_template_directory()));
}

if (!defined('CF_DIR_URI')) {
	define('CF_DIR_URI', untrailingslashit(get_template_directory_uri()));
}

if (!defined('CF_BUILD_URI')) {
	define('CF_BUILD_URI', untrailingslashit(get_template_directory_uri()) . '/static');
}

if (!defined('CF_BUILD_PATH')) {
	define('CF_BUILD_PATH', untrailingslashit(get_template_directory()) . '/static');
}

if (!defined('CF_BUILD_JS_URI')) {
	define('CF_BUILD_JS_URI', untrailingslashit(get_template_directory_uri()) . '/static/js');
}

if (!defined('CF_BUILD_JS_DIR_PATH')) {
	define('CF_BUILD_JS_DIR_PATH', untrailingslashit(get_template_directory()) . '/static/js');
}

if (!defined('CF_BUILD_IMG_URI')) {
	define('CF_BUILD_IMG_URI', untrailingslashit(get_template_directory_uri()) . '/static/src/img');
}

if (!defined('CF_BUILD_CSS_URI')) {
	define('CF_BUILD_CSS_URI', untrailingslashit(get_template_directory_uri()) . '/static/css');
}

if (!defined('CF_BUILD_CSS_DIR_PATH')) {
	define('CF_BUILD_CSS_DIR_PATH', untrailingslashit(get_template_directory()) . '/static/css');
}

if (!defined('CF_BUILD_LIB_URI')) {
	define('CF_BUILD_LIB_URI', untrailingslashit(get_template_directory_uri()) . '/static/library');
}


// *** THEME SETUP ***
include __DIR__.'/functions/setup/composer-packages.php';
include __DIR__.'/functions/setup/theme-setup.php';
include __DIR__.'/functions/setup/theme-assets.php';


// ACF Config JSON save point
add_filter('acf/settings/save_json', 'labo_acf_json_save_point');
function labo_acf_json_save_point( $path ) {
    unset($paths[0]);
    $path = get_stylesheet_directory() . '/acf-config/fields';
    return $path;
}

// ACF Config JSON load point
add_filter('acf/settings/load_json', 'labo_acf_json_load_point');
function labo_acf_json_load_point( $path ) {
   unset($path[0]);
   $path = get_stylesheet_directory() . '/acf-config/fields';
   return $path;
}

// ACF Blocks
include __DIR__.'/acf-config/blocks/acf-block-carousel-hero.php';
include __DIR__.'/acf-config/blocks/block-launch-two-images.php';

// Add custom category for components to Gutenberg editor
add_action( 'block_categories_all', 'labo_gutenberg_block_categories', 10, 2 );
function labo_gutenberg_block_categories( $categories ) {
	return array_merge(
		$categories,
		[
			[
				'slug'  => 'caffeina-theme',
				'title' => 'Caffeina Theme Components',
			],
		]
	);
}
