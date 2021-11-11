<?php

/**
 * The Template for displaying products in a product category. Simply includes the archive template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/taxonomy-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     4.7.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}



function get_category_parents_custom($category_id)
{
    $args = array(
        'separator' => ',',
        'link'      => false,
        'format'    => 'slug',
    );

    $parent_terms = get_term_parents_list($category_id, 'product_cat', $args);

    return substr_count($parent_terms, ',');
}
// Current term
$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
// Direct parents of current taxonomy
$level = get_category_parents_custom($term->term_id);

$context = [];



switch ($level) {
    // Macro
    case 1:
        $product_cat_parents = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => $term->term_id
        ));

        $context = [
            'level' => 'macro',
            'data' => [
                'terms' => $product_cat_parents
            ]
        ];

        Timber::render('@PathViews/woo/taxonomy-product-cat.twig', $context);
        break;


    // Zona
    case 2:
        $product_cat_parents = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => $term->term_id
        ));

        $context = [
            'level' => 'zona',
            'data' => [
                'terms' => $product_cat_parents
            ]
        ];

        Timber::render('@PathViews/woo/taxonomy-product-cat.twig', $context);
        break;


    // Esigenza
    case 3:
        $res = [];
        $brands_arr = [];

        $tipologie = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => $term->term_id,
            // 'fields' => 'slugs',
        ));

        // Get only slugs
        $tipologie_slugs = array_map(function($obj) { return $obj->slug;}, $tipologie);

        $brands = get_terms(array(
            'taxonomy' => 'lb-brand',
            'hide_empty' => false
        ));

        foreach ($brands as $brand) {
            $the_query = new WP_Query(array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $tipologie_slugs,
                    ),
                    array(
                        'taxonomy' => 'lb-brand',
                        'field' => 'slug',
                        'terms' => $brand->slug,
                    )
                ),
            ));

            if (count($the_query->posts) > 0) {
                $brands_arr[] = $brand;
                $res[] = [
                    'brand' => $brand,
                    'products' => $the_query->posts,
                ];
            }
        }

        wp_reset_postdata();

        $context = [
            'level' => 'esigenza',
            'data' => [
                'brands' => $brands_arr,
                'tipologie' => $tipologie,
                'results' => $res
            ],
        ];

        Timber::render('@PathViews/woo/taxonomy-product-cat.twig', $context);
        break;


    // Tipologia
    case 4:
        echo "Tipologia";
        // $context = [
        //     'level' => 'tipologia',
        //     'data' => []
        // ];
        // Timber::render('@PathViews/woo/taxonomy-product-cat.twig', $context);
        break;


    // Default
    default:
        echo "Level others";
}
