<?php

/**
 * Registers the lb-brand taxonomy
 */
function lb_brand_tax_init()
{
    $args = array(
        'labels' => array(
            'name'              => _x('Brands', 'taxonomy general name', 'lb-brand-tax'),
            'singular_name'     => _x('Brand', 'taxonomy singular name', 'lb-brand-tax'),
            'search_items'      => __('Cerca i Brand', 'lb-brand-tax'),
            'all_items'         => __('Tutti i Brand', 'lb-brand-tax'),
            'parent_item'       => __('Parent Brand', 'lb-brand-tax'),
            'parent_item_colon' => __('Parent Brand:', 'lb-brand-tax'),
            'edit_item'         => __('Modifica Brand', 'lb-brand-tax'),
            'update_item'       => __('Aggiorna Brand', 'lb-brand-tax'),
            'add_new_item'      => __('Aggiungi nuova Brand', 'lb-brand-tax'),
            'new_item_name'     => __('Nuovo nome Brand', 'lb-brand-tax'),
            'menu_name'         => __('Brand', 'lb-brand-tax'),
        ),
        'description' => '',
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => false,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'brands', 'with_front' => true)
    );
    register_taxonomy('lb-brand', array('product'), $args);
}
add_action('init', 'lb_brand_tax_init');



/**
 * Registers the lb-post-typology taxonomy
 */
function lb_post_typology_tax_init()
{
    $args = array(
        'labels' => array(
            'name'              => _x('Tipologie', 'taxonomy general name', 'lb-post-typology-tax'),
            'singular_name'     => _x('Tipologia', 'taxonomy singular name', 'lb-post-typology-tax'),
            'search_items'      => __('Cerca Tipologie', 'lb-post-typology-tax'),
            'all_items'         => __('Tutte le Tipologie', 'lb-post-typology-tax'),
            'parent_item'       => __('Parent Tipologia', 'lb-post-typology-tax'),
            'parent_item_colon' => __('Parent Tipologia:', 'lb-post-typology-tax'),
            'edit_item'         => __('Modifica Tipologia', 'lb-post-typology-tax'),
            'update_item'       => __('Aggiornas Tipologia', 'lb-post-typology-tax'),
            'add_new_item'      => __('Aggiungi nuova Tipologia', 'lb-post-typology-tax'),
            'new_item_name'     => __('Nuovo nome Tipologia', 'lb-post-typology-tax'),
            'menu_name'         => __('Tipologia', 'lb-post-typology-tax'),
        ),
        'description' => '',
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => false,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
    );
    register_taxonomy('lb-post-typology', array('post'), $args);
}
add_action('init', 'lb_post_typology_tax_init');



/**
 * Register Custom Post Type Brand page
 */
// function lb_brand_page_cpt_init()
// {
//     $args = array(
//         'label' => __('Brand page', 'lb-brand-page-cpt'),
//         'labels' => array(
//             'name' => _x('Brand pages', 'Post Type General Name', 'lb-brand-page-cpt'),
//             'singular_name' => _x('Brand page', 'Post Type Singular Name', 'lb-brand-page-cpt'),
//             'menu_name' => _x('Brand pages', 'Admin Menu text', 'lb-brand-page-cpt'),
//             'name_admin_bar' => _x('Brand page', 'Add New on Toolbar', 'lb-brand-page-cpt'),
//             'archives' => __('Archivi Brand page', 'lb-brand-page-cpt'),
//             'attributes' => __('Attributi delle Brand page', 'lb-brand-page-cpt'),
//             'parent_item_colon' => __('Genitori Brand page:', 'lb-brand-page-cpt'),
//             'all_items' => __('Tutti gli Brand pages', 'lb-brand-page-cpt'),
//             'add_new_item' => __('Aggiungi nuovo Brand page', 'lb-brand-page-cpt'),
//             'add_new' => __('Nuovo', 'lb-brand-page-cpt'),
//             'new_item' => __('Brand page redigere', 'lb-brand-page-cpt'),
//             'edit_item' => __('Modifica Brand page', 'lb-brand-page-cpt'),
//             'update_item' => __('Aggiorna Brand page', 'lb-brand-page-cpt'),
//             'view_item' => __('Visualizza Brand page', 'lb-brand-page-cpt'),
//             'view_items' => __('Visualizza gli Brand pages', 'lb-brand-page-cpt'),
//             'search_items' => __('Cerca Brand page', 'lb-brand-page-cpt'),
//             'not_found' => __('Nessun Brand pages trovato.', 'lb-brand-page-cpt'),
//             'not_found_in_trash' => __('Nessun Brand pages trovato nel cestino.', 'lb-brand-page-cpt'),
//             'featured_image' => __('Immagine in evidenza', 'lb-brand-page-cpt'),
//             'set_featured_image' => __('Imposta immagine in evidenza', 'lb-brand-page-cpt'),
//             'remove_featured_image' => __('Rimuovi immagine in evidenza', 'lb-brand-page-cpt'),
//             'use_featured_image' => __('Usa come immagine in evidenza', 'lb-brand-page-cpt'),
//             'insert_into_item' => __('Inserisci nelle Brand page', 'lb-brand-page-cpt'),
//             'uploaded_to_this_item' => __('Caricato in questo Brand page', 'lb-brand-page-cpt'),
//             'items_list' => __('Elenco degli Brand pages', 'lb-brand-page-cpt'),
//             'items_list_navigation' => __('Navigazione elenco Brand pages', 'lb-brand-page-cpt'),
//             'filter_items_list' => __('Filtra elenco Brand pages', 'lb-brand-page-cpt'),
//         ),
//         'menu_icon' => 'dashicons-media-default',
//         'supports' => array('title', 'editor', 'thumbnail'),
//         'taxonomies' => array(),
//         'public' => false,
//         'show_ui' => true,
//         'show_in_menu' => true,
//         'menu_position' => 5,
//         'show_in_admin_bar' => true,
//         'show_in_nav_menus' => true,
//         'can_export' => true,
//         'has_archive' => false,
//         'hierarchical' => false,
//         'exclude_from_search' => false,
//         'show_in_rest' => true,
//         'publicly_queryable' => false,
//         'capability_type' => 'page',
//     );
//     register_post_type('lb-brand-page', $args);
// }
// add_action('init', 'lb_brand_page_cpt_init', 0);
