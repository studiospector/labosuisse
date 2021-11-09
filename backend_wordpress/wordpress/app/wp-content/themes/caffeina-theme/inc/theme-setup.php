<?php

/**
 * Setup theme
 */
add_action('after_setup_theme', 'setup_theme');
function setup_theme()
{
    // load_theme_textdomain( 'abbrivio', ABBRIVIO_DIR_PATH . '/languages' );

    add_theme_support('title-tag');

    add_theme_support('post-thumbnails');

    add_theme_support('post-formats', array('aside', 'gallery'));

    add_image_size('cf-large', 1260, 600, true);
    add_image_size('cf-medium', 650, 470, true);
    add_image_size('cf-small', 400, 345, true);


    add_theme_support('customize-selective-refresh-widgets');

    add_theme_support(
        'html5',
        [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'script',
            'style',
        ]
    );

    // Gutenberg theme support
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');

    /**
     * Path to our custom editor style
     * It allows you to link a custom stylesheet file to the TinyMCE editor within the post edit screen
     */
    // add_editor_style('gutenberg/editor.css');

    // Remove the core block patterns
    remove_theme_support('core-block-patterns');

    /**
     * Set the maximum allowed width for any content in the theme
     * like oEmbeds and images added to posts
     */
    global $content_width;
    if (!isset($content_width)) {
        $content_width = 1240;
    }
}



/**
 * ACF Config
 */
// ACF Config JSON save point
add_filter('acf/settings/save_json', 'labo_acf_json_save_point');
function labo_acf_json_save_point($path)
{
    unset($paths[0]);
    $path = get_stylesheet_directory() . '/acf-config/fields';
    return $path;
}

// ACF Config JSON load point
add_filter('acf/settings/load_json', 'labo_acf_json_load_point');
function labo_acf_json_load_point($path)
{
    unset($path[0]);
    $path = get_stylesheet_directory() . '/acf-config/fields';
    return $path;
}



/**
 * Add custom category for components to Gutenberg editor
 */
add_action('block_categories_all', 'labo_gutenberg_block_categories', 10, 2);
function labo_gutenberg_block_categories($categories)
{
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
