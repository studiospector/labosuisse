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
 * ACF Blocks
 */
include __DIR__ . '/acf-config/blocks/acf-block-carousel-hero.php';
include __DIR__ . '/acf-config/blocks/acf-block-launch-two-images.php';
