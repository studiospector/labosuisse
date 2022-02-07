<?php

/**
 * Registers the lb-brand taxonomy
 */
function lb_brand_tax_init() {

	$args = array(
		'labels' => array(
            'name'              => _x( 'Brands', 'taxonomy general name', 'lb-brand-tax' ),
            'singular_name'     => _x( 'Brand', 'taxonomy singular name', 'lb-brand-tax' ),
            'search_items'      => __( 'Cerca i Brand', 'lb-brand-tax' ),
            'all_items'         => __( 'Tutti i Brand', 'lb-brand-tax' ),
            'parent_item'       => __( 'Parent Brand', 'lb-brand-tax' ),
            'parent_item_colon' => __( 'Parent Brand:', 'lb-brand-tax' ),
            'edit_item'         => __( 'Modifica Brand', 'lb-brand-tax' ),
            'update_item'       => __( 'Aggiorna Brand', 'lb-brand-tax' ),
            'add_new_item'      => __( 'Aggiungi nuova Brand', 'lb-brand-tax' ),
            'new_item_name'     => __( 'Nuovo nome Brand', 'lb-brand-tax' ),
            'menu_name'         => __( 'Brand', 'lb-brand-tax' ),
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
	register_taxonomy( 'lb-brand', array('product'), $args );
}
add_action( 'init', 'lb_brand_tax_init' );



/**
 * Registers the lb-post-typology taxonomy
 */
function lb_post_typology_tax_init() {

	$args = array(
		'labels' => array(
            'name'              => _x( 'Tipologie', 'taxonomy general name', 'lb-post-typology-tax' ),
            'singular_name'     => _x( 'Tipologia', 'taxonomy singular name', 'lb-post-typology-tax' ),
            'search_items'      => __( 'Cerca Tipologie', 'lb-post-typology-tax' ),
            'all_items'         => __( 'Tutte le Tipologie', 'lb-post-typology-tax' ),
            'parent_item'       => __( 'Parent Tipologia', 'lb-post-typology-tax' ),
            'parent_item_colon' => __( 'Parent Tipologia:', 'lb-post-typology-tax' ),
            'edit_item'         => __( 'Modifica Tipologia', 'lb-post-typology-tax' ),
            'update_item'       => __( 'Aggiornas Tipologia', 'lb-post-typology-tax' ),
            'add_new_item'      => __( 'Aggiungi nuova Tipologia', 'lb-post-typology-tax' ),
            'new_item_name'     => __( 'Nuovo nome Tipologia', 'lb-post-typology-tax' ),
            'menu_name'         => __( 'Tipologia', 'lb-post-typology-tax' ),
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
	register_taxonomy( 'lb-post-typology', array('post'), $args );
}
add_action( 'init', 'lb_post_typology_tax_init' );
