<?php

/**
 * Enqueue site scripts
 */
add_action('wp_enqueue_scripts', 'lb_register_scripts');
function lb_register_scripts()
{
    // // Register scripts
    // wp_register_script('slick-js', LB_BUILD_LIB_URI . '/js/slick.min.js', ['jquery'], false, true);
    // wp_register_script('lb-main-js', LB_BUILD_JS_URI . '/main.js', ['jquery', 'slick-js'], filemtime(LB_BUILD_JS_DIR_PATH . '/main.js'), true);

    // // Enqueue scripts
    // wp_enqueue_script('slick-js');
    // wp_enqueue_script('lb-main-js');
}



/**
 * Enqueue site style
 */
add_action('wp_enqueue_scripts', 'lb_register_styles');
function lb_register_styles()
{
    // // Register styles
    // wp_register_style('slick-css', LB_BUILD_LIB_URI . '/css/slick.css', [], false, 'all');
    // // wp_register_style('slick-theme-css', LB_BUILD_LIB_URI . '/css/slick-theme.css', ['slick-css'], false, 'all');
    // wp_register_style('lb-main-css', LB_BUILD_CSS_URI . '/main.css', [], filemtime(LB_BUILD_CSS_DIR_PATH . '/main.css'), 'all');

    // // Enqueue style
    // wp_enqueue_style('slick-css');
    // // wp_enqueue_style('slick-theme-css');
    // wp_enqueue_style('lb-main-css');

    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wp-block-library-css');
}



/**
 * Enqueue admin style
 */
add_action('admin_enqueue_scripts', 'lb_register_admin_styles');
function lb_register_admin_styles()
{
    // Register styles
    wp_register_style('lb-custom-admin', LB_BUILD_CSS_URI . '/admin.css', [], filemtime(LB_BUILD_CSS_DIR_PATH . '/admin.css'), 'all');

    // Enqueue Styles
    wp_enqueue_style('lb-custom-admin');
}



/**
 * Enqueue admin login page style
 */
add_action('login_enqueue_scripts', 'lb_enqueue_admin_login_scripts');
function lb_enqueue_admin_login_scripts()
{
    wp_enqueue_style('lb-login-custom-style', LB_BUILD_CSS_URI . '/admin.css', 'all');
}



/**
 * Enqueue editor scripts and styles
 * 
 * Note:
 * The 'enqueue_block_assets' hook includes styles and scripts both in editor and frontend
 * except when is_admin() is used to include them conditionally
 */
add_action('enqueue_block_assets', 'lb_enqueue_editor_assets');
function lb_enqueue_editor_assets()
{
    if (is_admin()) {
        // Gutenberg blocks JS
        wp_enqueue_script(
            'lb-blocks-js',
            LB_BUILD_JS_URI . '/blocks.js',
            ['wp-polyfill'],
            filemtime(LB_BUILD_JS_DIR_PATH . '/blocks.js'),
            true
        );

        // Gutenberg blocks CSS
        wp_enqueue_style(
            'lb-blocks-css',
            LB_BUILD_CSS_URI . '/blocks.css',
            ['wp-block-library-theme', 'wp-block-library',],
            filemtime(LB_BUILD_CSS_DIR_PATH . '/blocks.css'),
            'all'
        );
    }
}
