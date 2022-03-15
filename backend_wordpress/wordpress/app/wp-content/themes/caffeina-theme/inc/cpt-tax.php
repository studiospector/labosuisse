<?php

use Caffeina\LaboSuisse\Actions\Store\UpdateGoogleMapsData;
use Caffeina\LaboSuisse\Option\Option;

/**
 * Register Custom Post Type Brand page
 */
function lb_brand_page_cpt_init()
{
    $args = array(
        'label' => __('Brand page', 'lb-brand-page-cpt'),
        'labels' => array(
            'name' => _x('Brand pages', 'Post Type General Name', 'lb-brand-page-cpt'),
            'singular_name' => _x('Brand page', 'Post Type Singular Name', 'lb-brand-page-cpt'),
            'menu_name' => _x('Brand pages', 'Admin Menu text', 'lb-brand-page-cpt'),
            'name_admin_bar' => _x('Brand page', 'Add New on Toolbar', 'lb-brand-page-cpt'),
            'archives' => __('Archivi Brand page', 'lb-brand-page-cpt'),
            'attributes' => __('Attributi delle Brand page', 'lb-brand-page-cpt'),
            'parent_item_colon' => __('Genitori Brand page:', 'lb-brand-page-cpt'),
            'all_items' => __('Tutte le Brand pages', 'lb-brand-page-cpt'),
            'add_new_item' => __('Aggiungi nuova Brand page', 'lb-brand-page-cpt'),
            'add_new' => __('Nuovo', 'lb-brand-page-cpt'),
            'new_item' => __('Brand page redigere', 'lb-brand-page-cpt'),
            'edit_item' => __('Modifica Brand page', 'lb-brand-page-cpt'),
            'update_item' => __('Aggiorna Brand page', 'lb-brand-page-cpt'),
            'view_item' => __('Visualizza Brand page', 'lb-brand-page-cpt'),
            'view_items' => __('Visualizza le Brand pages', 'lb-brand-page-cpt'),
            'search_items' => __('Cerca Brand page', 'lb-brand-page-cpt'),
            'not_found' => __('Nessun Brand pages trovato.', 'lb-brand-page-cpt'),
            'not_found_in_trash' => __('Nessun Brand pages trovato nel cestino.', 'lb-brand-page-cpt'),
            'featured_image' => __('Immagine in evidenza', 'lb-brand-page-cpt'),
            'set_featured_image' => __('Imposta immagine in evidenza', 'lb-brand-page-cpt'),
            'remove_featured_image' => __('Rimuovi immagine in evidenza', 'lb-brand-page-cpt'),
            'use_featured_image' => __('Usa come immagine in evidenza', 'lb-brand-page-cpt'),
            'insert_into_item' => __('Inserisci nelle Brand page', 'lb-brand-page-cpt'),
            'uploaded_to_this_item' => __('Caricato in questo Brand page', 'lb-brand-page-cpt'),
            'items_list' => __('Elenco degli Brand pages', 'lb-brand-page-cpt'),
            'items_list_navigation' => __('Navigazione elenco Brand pages', 'lb-brand-page-cpt'),
            'filter_items_list' => __('Filtra elenco Brand pages', 'lb-brand-page-cpt'),
        ),
        'menu_icon' => 'dashicons-media-default',
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array(),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => false,
        'hierarchical' => false,
        'exclude_from_search' => true,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'capability_type' => 'page',
        'rewrite' => array('slug' => 'brand-page'),
    );
    register_post_type('lb-brand-page', $args);
}
add_action('init', 'lb_brand_page_cpt_init', 0);



/**
 * Register Custom Post Type FAQ
 */
function lb_faq_cpt_init()
{
    $args = array(
        'label' => __('FAQ', 'lb-faq-cpt'),
        'labels' => array(
            'name' => _x('FAQ', 'Post Type General Name', 'lb-faq-cpt'),
            'singular_name' => _x('FAQ', 'Post Type Singular Name', 'lb-faq-cpt'),
            'menu_name' => _x('FAQ', 'Admin Menu text', 'lb-faq-cpt'),
            'name_admin_bar' => _x('FAQ', 'Add New on Toolbar', 'lb-faq-cpt'),
            'archives' => __('Archivi FAQ', 'lb-faq-cpt'),
            'attributes' => __('Attributi delle FAQ', 'lb-faq-cpt'),
            'parent_item_colon' => __('Genitori FAQ:', 'lb-faq-cpt'),
            'all_items' => __('Tutte le FAQ', 'lb-faq-cpt'),
            'add_new_item' => __('Aggiungi nuova FAQ', 'lb-faq-cpt'),
            'add_new' => __('Nuova', 'lb-faq-cpt'),
            'new_item' => __('FAQ redigere', 'lb-faq-cpt'),
            'edit_item' => __('Modifica FAQ', 'lb-faq-cpt'),
            'update_item' => __('Aggiorna FAQ', 'lb-faq-cpt'),
            'view_item' => __('Visualizza FAQ', 'lb-faq-cpt'),
            'view_items' => __('Visualizza le FAQ', 'lb-faq-cpt'),
            'search_items' => __('Cerca FAQ', 'lb-faq-cpt'),
            'not_found' => __('Nessuna FAQ trovata.', 'lb-faq-cpt'),
            'not_found_in_trash' => __('Nessuna FAQ trovata nel cestino.', 'lb-faq-cpt'),
            'featured_image' => __('Immagine in evidenza', 'lb-faq-cpt'),
            'set_featured_image' => __('Imposta immagine in evidenza', 'lb-faq-cpt'),
            'remove_featured_image' => __('Rimuovi immagine in evidenza', 'lb-faq-cpt'),
            'use_featured_image' => __('Usa come immagine in evidenza', 'lb-faq-cpt'),
            'insert_into_item' => __('Inserisci nelle FAQ', 'lb-faq-cpt'),
            'uploaded_to_this_item' => __('Caricato in questo FAQ', 'lb-faq-cpt'),
            'items_list' => __('Elenco delle FAQ', 'lb-faq-cpt'),
            'items_list_navigation' => __('Navigazione elenco FAQ', 'lb-faq-cpt'),
            'filter_items_list' => __('Filtra elenco FAQ', 'lb-faq-cpt'),
        ),
        'menu_icon' => 'dashicons-testimonial',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies' => array(),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'exclude_from_search' => false,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'map_meta_cap' => true,
        'capability_type' => array( 'lb-faq', 'lb-faqs' ),
        'rewrite' => array('slug' => 'faq'),
    );
    register_post_type('lb-faq', $args);
}
add_action('init', 'lb_faq_cpt_init', 0);



/**
 * Register Custom Post Type Job
 */
function lb_jobs_cpt_init()
{
    $args = array(
        'label' => __('Jobs', 'lb-job-cpt'),
        'labels' => array(
            'name' => _x('Jobs', 'Post Type General Name', 'lb-job-cpt'),
            'singular_name' => _x('Job', 'Post Type Singular Name', 'lb-job-cpt'),
            'menu_name' => _x('Jobs', 'Admin Menu text', 'lb-job-cpt'),
            'name_admin_bar' => _x('Job', 'Add New on Toolbar', 'lb-job-cpt'),
            'archives' => __('Archivi Jobs', 'lb-job-cpt'),
            'attributes' => __('Attributi delle Jobs', 'lb-job-cpt'),
            'parent_item_colon' => __('Genitori Jobs:', 'lb-job-cpt'),
            'all_items' => __('Tutti i Jobs', 'lb-job-cpt'),
            'add_new_item' => __('Aggiungi nuovo Jobs', 'lb-job-cpt'),
            'add_new' => __('Nuovo', 'lb-job-cpt'),
            'new_item' => __('Jobs redigere', 'lb-job-cpt'),
            'edit_item' => __('Modifica Job', 'lb-job-cpt'),
            'update_item' => __('Aggiorna Job', 'lb-job-cpt'),
            'view_item' => __('Visualizza Job', 'lb-job-cpt'),
            'view_items' => __('Visualizza i Jobs', 'lb-job-cpt'),
            'search_items' => __('Cerca Jobs', 'lb-job-cpt'),
            'not_found' => __('Nessun Job trovato.', 'lb-job-cpt'),
            'not_found_in_trash' => __('Nessun Job trovato nel cestino.', 'lb-job-cpt'),
            'featured_image' => __('Immagine in evidenza', 'lb-job-cpt'),
            'set_featured_image' => __('Imposta immagine in evidenza', 'lb-job-cpt'),
            'remove_featured_image' => __('Rimuovi immagine in evidenza', 'lb-job-cpt'),
            'use_featured_image' => __('Usa come immagine in evidenza', 'lb-job-cpt'),
            'insert_into_item' => __('Inserisci nel Job', 'lb-job-cpt'),
            'uploaded_to_this_item' => __('Caricato in questo Job', 'lb-job-cpt'),
            'items_list' => __('Elenco dei Jobs', 'lb-job-cpt'),
            'items_list_navigation' => __('Navigazione elenco Jobs', 'lb-job-cpt'),
            'filter_items_list' => __('Filtra elenco Jobs', 'lb-job-cpt'),
        ),
        'menu_icon' => 'dashicons-businesswoman',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies' => array(),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'exclude_from_search' => false,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'map_meta_cap' => true,
        'capability_type' => array( 'lb-job', 'lb-jobs' ),
        'rewrite' => array('slug' => 'job'),
    );
    register_post_type('lb-job', $args);
}
add_action('init', 'lb_jobs_cpt_init', 0);



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
        'rewrite' => array('slug' => 'brands', 'with_front' => true, 'hierarchical' => true)
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
            'update_item'       => __('Aggiorna Tipologia', 'lb-post-typology-tax'),
            'add_new_item'      => __('Aggiungi nuova Tipologia', 'lb-post-typology-tax'),
            'new_item_name'     => __('Nuovo nome Tipologia', 'lb-post-typology-tax'),
            'menu_name'         => __('Tipologia', 'lb-post-typology-tax'),
        ),
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
 * Registers the lb-job-location taxonomy
 */
function lb_job_location_tax_init()
{
    $args = array(
        'labels' => array(
            'name'              => _x('Locations', 'taxonomy general name', 'lb-job-location-tax'),
            'singular_name'     => _x('Location', 'taxonomy singular name', 'lb-job-location-tax'),
            'search_items'      => __('Cerca Locations', 'lb-job-location-tax'),
            'all_items'         => __('Tutte le Locations', 'lb-job-location-tax'),
            'parent_item'       => __('Parent Location', 'lb-job-location-tax'),
            'parent_item_colon' => __('Parent Location:', 'lb-job-location-tax'),
            'edit_item'         => __('Modifica Location', 'lb-job-location-tax'),
            'update_item'       => __('Aggiorna Location', 'lb-job-location-tax'),
            'add_new_item'      => __('Aggiungi nuova Location', 'lb-job-location-tax'),
            'new_item_name'     => __('Nuovo nome Location', 'lb-job-location-tax'),
            'menu_name'         => __('Location', 'lb-job-location-tax'),
        ),
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
    register_taxonomy('lb-job-location', array('lb-job'), $args);
}
add_action('init', 'lb_job_location_tax_init');



/**
 * Registers the lb-job-department taxonomy
 */
function lb_job_department_tax_init()
{
    $args = array(
        'labels' => array(
            'name'              => _x('Dipartimenti', 'taxonomy general name', 'lb-job-department-tax'),
            'singular_name'     => _x('Dipartimento', 'taxonomy singular name', 'lb-job-department-tax'),
            'search_items'      => __('Cerca Dipartimenti', 'lb-job-department-tax'),
            'all_items'         => __('Tutti i Dipartimenti', 'lb-job-department-tax'),
            'parent_item'       => __('Parent Dipartimento', 'lb-job-department-tax'),
            'parent_item_colon' => __('Parent Dipartimento:', 'lb-job-department-tax'),
            'edit_item'         => __('Modifica Dipartimento', 'lb-job-department-tax'),
            'update_item'       => __('Aggiorna Dipartimento', 'lb-job-department-tax'),
            'add_new_item'      => __('Aggiungi nuovo Dipartimento', 'lb-job-department-tax'),
            'new_item_name'     => __('Nuovo nome Dipartimento', 'lb-job-department-tax'),
            'menu_name'         => __('Dipartimento', 'lb-job-department-tax'),
        ),
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
    register_taxonomy('lb-job-department', array('lb-job'), $args);
}
add_action('init', 'lb_job_department_tax_init');


/**
 * Register Custom Post Type Stores
 */
function lb_stores_cpt_init()
{
    $args = array(
        'label' => __('Stores', 'lb-store-cpt'),
        'labels' => array(
            'name' => _x('Stores', 'Post Type General Name', 'lb-store-cpt'),
            'singular_name' => _x('Store', 'Post Type Singular Name', 'lb-store-cpt'),
            'menu_name' => _x('Stores', 'Admin Menu text', 'lb-store-cpt'),
            'name_admin_bar' => _x('Store', 'Add New on Toolbar', 'lb-store-cpt'),
            'archives' => __('Archivi Stores', 'lb-store-cpt'),
            'attributes' => __('Attributi degli Stores', 'lb-store-cpt'),
            'parent_item_colon' => __('Genitori Stores:', 'lb-store-cpt'),
            'all_items' => __('Tutti gli Stores', 'lb-store-cpt'),
            'add_new_item' => __('Aggiungi nuovo Store', 'lb-store-cpt'),
            'add_new' => __('Nuovo', 'lb-store-cpt'),
            'new_item' => __('Stores redigere', 'lb-store-cpt'),
            'edit_item' => __('Modifica Store', 'lb-store-cpt'),
            'update_item' => __('Aggiorna Store', 'lb-store-cpt'),
            'view_item' => __('Visualizza Store', 'lb-store-cpt'),
            'view_items' => __('Visualizza gli Stores', 'lb-store-cpt'),
            'search_items' => __('Cerca Stores', 'lb-store-cpt'),
            'not_found' => __('Nessun Store trovato.', 'lb-store-cpt'),
            'not_found_in_trash' => __('Nessun Store trovato nel cestino.', 'lb-store-cpt'),
            'featured_image' => __('Immagine in evidenza', 'lb-store-cpt'),
            'set_featured_image' => __('Imposta immagine in evidenza', 'lb-store-cpt'),
            'remove_featured_image' => __('Rimuovi immagine in evidenza', 'lb-store-cpt'),
            'use_featured_image' => __('Usa come immagine in evidenza', 'lb-store-cpt'),
            'insert_into_item' => __('Inserisci nello Store', 'lb-store-cpt'),
            'uploaded_to_this_item' => __('Caricato in questo Store', 'lb-store-cpt'),
            'items_list' => __('Elenco degli Stores', 'lb-store-cpt'),
            'items_list_navigation' => __('Navigazione elenco Stores', 'lb-store-cpt'),
            'filter_items_list' => __('Filtra elenco Stores', 'lb-store-cpt'),
        ),
        'menu_icon' => 'dashicons-store',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies' => array(),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'exclude_from_search' => false,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'map_meta_cap' => true,
        'capability_type' => array( 'lb-store', 'lb-stores' ),
        'rewrite' => array('slug' => 'store'),
    );
    register_post_type('lb-store', $args);
}
add_action('init', 'lb_stores_cpt_init', 0);

/**
 * Register Custom Post Type Stores
 */
function lb_beauty_specialist_cpt_init()
{
    $args = array(
        'label' => __('Beauty Specialist', 'lb-beauty-specialist-cpt'),
        'labels' => array(
            'name' => _x('Beauty Specialist', 'Post Type General Name', 'lb-beauty-specialist-cpt'),
            'singular_name' => _x('Beauty Specialist', 'Post Type Singular Name', 'lb-beauty-specialist-cpt'),
            'menu_name' => _x('Beauty Specialist', 'Admin Menu text', 'lb-beauty-specialist-cpt'),
            'name_admin_bar' => _x('Beauty Specialist', 'Add New on Toolbar', 'lb-beauty-specialist-cpt'),
            'archives' => __('Archivi Beauty Specialist', 'lb-beauty-specialist-cpt'),
            'attributes' => __('Attributi delle Beauty Specialist', 'lb-beauty-specialist-cpt'),
            'parent_item_colon' => __('Genitori Beauty Specialist:', 'lb-beauty-specialist-cpt'),
            'all_items' => __('Tutte le Beauty Specialist', 'lb-beauty-specialist-cpt'),
            'add_new_item' => __('Aggiungi nuova Beauty Specialist', 'lb-beauty-specialist-cpt'),
            'add_new' => __('Nuovo', 'lb-beauty-specialist-cpt'),
            'new_item' => __('Beauty Specialist redigere', 'lb-beauty-specialist-cpt'),
            'edit_item' => __('Modifica Beauty Specialist', 'lb-beauty-specialist-cpt'),
            'update_item' => __('Aggiorna Beauty Specialist', 'lb-beauty-specialist-cpt'),
            'view_item' => __('Visualizza Beauty Specialist', 'lb-beauty-specialist-cpt'),
            'view_items' => __('Visualizza le Beauty Specialist', 'lb-beauty-specialist-cpt'),
            'search_items' => __('Cerca Beauty Specialist', 'lb-beauty-specialist-cpt'),
            'not_found' => __('Nessuna Beauty Specialist trovata.', 'lb-beauty-specialist-cpt'),
            'not_found_in_trash' => __('Nessuna Beauty Specialist trovata nel cestino.', 'lb-beauty-specialist-cpt'),
            'featured_image' => __('Immagine in evidenza', 'lb-beauty-specialist-cpt'),
            'set_featured_image' => __('Imposta immagine in evidenza', 'lb-beauty-specialist-cpt'),
            'remove_featured_image' => __('Rimuovi immagine in evidenza', 'lb-beauty-specialist-cpt'),
            'use_featured_image' => __('Usa come immagine in evidenza', 'lb-beauty-specialist-cpt'),
            'insert_into_item' => __('Inserisci nella Beauty Specialist', 'lb-beauty-specialist-cpt'),
            'uploaded_to_this_item' => __('Caricato in questa Beauty Specialist', 'lb-beauty-specialist-cpt'),
            'items_list' => __('Elenco delle Beauty Specialist', 'lb-beauty-specialist-cpt'),
            'items_list_navigation' => __('Navigazione elenco Beauty Specialist', 'lb-beauty-specialist-cpt'),
            'filter_items_list' => __('Filtra elenco Beauty Specialist', 'lb-beauty-specialist-cpt'),
        ),
        'menu_icon' => 'dashicons-store',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies' => array(),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'exclude_from_search' => false,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'map_meta_cap' => true,
        'capability_type' => array( 'lb-beauty-specialist', 'lb-beauties-specialist' ),
        'rewrite' => array('slug' => 'beauty-specialist'),
    );
    register_post_type('lb-beauty-specialist', $args);
}
add_action('init', 'lb_beauty_specialist_cpt_init', 0);


// Register Services API Key
function lb_set_services_api_key() {
    //Google
    acf_update_setting('google_api_key', (new Option())->getApiKey('lb_api_key_google_maps'));
}
add_action('acf/init', 'lb_set_services_api_key');


function after_xml_import( $import_id, $import ) {
    $store = new UpdateGoogleMapsData();
    $store->start();
}
add_action( 'pmxi_after_xml_import', 'after_xml_import', 10, 2 );
