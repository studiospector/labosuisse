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



// Composer autoload
// require_once( __DIR__ . '/vendor/autoload.php' );

// *** THEME SETUP ***
include __DIR__.'/functions/setup/composer-packages.php';
include __DIR__.'/functions/setup/theme-setup.php';
include __DIR__.'/functions/setup/theme-assets.php';
// add_theme_support('post-thumbnails');
// add_filter('use_block_editor_for_post', '__return_false');
// include __DIR__.'/functions/setup/configure-wp-editor.php';
// include __DIR__.'/functions/setup/disable-comments.php';
// include __DIR__.'/functions/setup/disable-front-page-redirect.php';
// include __DIR__.'/functions/setup/mime-types.php';
// include __DIR__.'/functions/setup/pagination-custom-permalink.php';
// include __DIR__.'/functions/setup/transform-permalink.php';
// include __DIR__.'/functions/setup/virtual-pages.php';

// *** THEME API ***
// include __DIR__.'/functions/api/cpt.php';
// include __DIR__.'/functions/api/rest-headless-cms.php';
// include __DIR__.'/functions/api/menus.php';
// include __DIR__.'/functions/api/options.php';

// *** THEME UTILS ***
// include __DIR__.'/functions/utils/get-meta-tags-from-string.php';
// include __DIR__.'/functions/utils/json-response.php';
// include __DIR__.'/functions/utils/soft-trim.php';
// include __DIR__.'/functions/utils/transform-image-to-figure.php';
// include __DIR__.'/functions/utils/truncate-html.php';

// ACF Config
// include __DIR__.'/acf-config/acf-field-cf-book.php';
// include __DIR__.'/acf-config/acf-field-cf-loan.php';
// include __DIR__.'/acf-config/acf-field-cf-review.php';

// ACF Config JSON save point
add_filter('acf/settings/save_json', 'labo_acf_json_save_point');
function labo_acf_json_save_point( $path ) {
	// remove original path
    unset($paths[0]);
    // update path
    $path = get_stylesheet_directory() . '/acf-config/fields';

    return $path;
}

// ACF Config JSON load point
add_filter('acf/settings/load_json', 'labo_acf_json_load_point');
function labo_acf_json_load_point( $path ) {
   // remove original path
   unset($path[0]);
   // update path
   $path = get_stylesheet_directory() . '/acf-config/fields';

   return $path;
}

// ACF Blocks
include __DIR__.'/acf-config/blocks/acf-block-hero.php';
