<?php

// Enqueue site scripts
add_action('wp_enqueue_scripts', 'cf_register_scripts');
// Enqueue site style
add_action('wp_enqueue_scripts', 'cf_register_styles');
// Enqueue admin style
// add_action('admin_enqueue_scripts', 'cf_register_admin_styles');
/**
 * The 'enqueue_block_assets' hook includes styles and scripts both in editor and frontend
 * except when is_admin() is used to include them conditionally
 */
add_action('enqueue_block_assets', 'cf_enqueue_editor_assets');





/**
 * Enqueue site scripts
 */
function cf_register_scripts()
{
    // // Register scripts
    // wp_register_script('slick-js', CF_BUILD_LIB_URI . '/js/slick.min.js', ['jquery'], false, true);
    // wp_register_script('cf-main-js', CF_BUILD_JS_URI . '/main.js', ['jquery', 'slick-js'], filemtime(CF_BUILD_JS_DIR_PATH . '/main.js'), true);

    // // Enqueue scripts
    // wp_enqueue_script('slick-js');
    // wp_enqueue_script('cf-main-js');
}



/**
 * Enqueue site style
 */
function cf_register_styles()
{
    // // Register styles
    // wp_register_style('slick-css', CF_BUILD_LIB_URI . '/css/slick.css', [], false, 'all');
    // // wp_register_style('slick-theme-css', CF_BUILD_LIB_URI . '/css/slick-theme.css', ['slick-css'], false, 'all');
    // wp_register_style('cf-main-css', CF_BUILD_CSS_URI . '/main.css', [], filemtime(CF_BUILD_CSS_DIR_PATH . '/main.css'), 'all');

    // // Enqueue style
    // wp_enqueue_style('slick-css');
    // // wp_enqueue_style('slick-theme-css');
    // wp_enqueue_style('cf-main-css');
}



/**
 * Enqueue admin style
 */
function cf_register_admin_styles()
{
    // // Register styles
    // wp_register_style('cf-custom-admin', CF_BUILD_CSS_URI . '/admin.css', [], filemtime(CF_BUILD_CSS_DIR_PATH . '/admin.css'), 'all');

    // // Enqueue Styles
    // wp_enqueue_style('cf-custom-admin');
}



/**
 * Enqueue editor scripts and styles
 */
function cf_enqueue_editor_assets()
{
    $asset_config_file = sprintf('%s/assets.php', untrailingslashit(get_template_directory()) . '/gutenberg');

    if (!file_exists($asset_config_file)) {
        return;
    }

    $asset_config = require_once $asset_config_file;

    if (empty($asset_config['js/editor.js'])) {
        return;
    }

    $editor_asset    = $asset_config['js/editor.js'];
    $js_dependencies = (!empty($editor_asset['dependencies'])) ? $editor_asset['dependencies'] : [];
    $version         = (!empty($editor_asset['version'])) ? $editor_asset['version'] : filemtime($asset_config_file);

    // Theme Gutenberg blocks JS
    if (is_admin()) {
        wp_enqueue_script(
            'cf-blocks-js',
            CF_BUILD_JS_URI . '/blocks.js',
            $js_dependencies,
            $version,
            true
        );
    }

    // Theme Gutenberg blocks CSS
    $css_dependencies = [
        'wp-block-library-theme',
        'wp-block-library',
    ];

    wp_enqueue_style(
        'cf-blocks-css',
        CF_BUILD_CSS_URI . '/blocks.css',
        $css_dependencies,
        filemtime(CF_BUILD_CSS_DIR_PATH . '/blocks.css'),
        'all'
    );
}