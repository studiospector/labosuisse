<?php

namespace Caffeina\LaboSuisse\Setup;

class CustomTaxonomy
{
    public $brandArgs;
    public $postTypologyArgs;
    public $jobLocationArgs;
    public $jobDepartmentArgs;

    public function __construct()
    {
        // Brand
        $this->brandArgs = array(
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

        // Post Typology
        $this->postTypologyArgs = array(
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

        // Job Location
        $this->jobLocationArgs = array(
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

        // Job Department
        $this->jobDepartmentArgs = array(
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

        // Init CPTs
        add_action('init', [$this, 'lb_init_tax']);
    }

    public function lb_init_tax() {
        register_taxonomy('lb-brand', array('product', 'lb-distributor'), $this->brandArgs);
        register_taxonomy('lb-post-typology', array('post'), $this->postTypologyArgs);
        register_taxonomy('lb-job-location', array('lb-job'), $this->jobLocationArgs);
        register_taxonomy('lb-job-department', array('lb-job'), $this->jobDepartmentArgs);
    }
}
