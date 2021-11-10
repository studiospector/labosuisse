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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function get_category_parents_custom( $category_id ) {

    $args = array(
        'separator' => ',',
        'link'      => false,
        'format'    => 'slug',
    );

    return substr_count(get_term_parents_list( $category_id, 'product_cat', $args ),',');
}
//Search taxonomy current category
$term = get_term_by( 'slug', get_query_var('term'), get_query_var('taxonomy') );
//Search taxonomy current parents category
$level = get_category_parents_custom( $term->term_id );

$context = [];

// echo "<pre>";
// var_dump($term,$level);
// die;
switch ($level) {
  case 1:
    //echo "macro";
    $children = get_terms( array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'parent' => $term->term_id
    ) );
    // echo '<pre>';
    // var_dump( $children  );
    // die;
    $context = [
      'level' => 'macro',
      'data' => [
          'terms' => $children
      ]
    ];
    Timber::render('@PathViews/woo/taxonomy-product-cat.twig', $context);
  break;
  case 2:
     //echo "macro";
     $children = get_terms( array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'parent' => $term->term_id
    ) );
    // echo '<pre>';
    // var_dump( $children  );
    // die;
    $context = [
      'level' => 'zona',
      'data' => [
          'terms' => $children
      ]
    ];

    Timber::render('@PathViews/woo/taxonomy-product-cat.twig', $context);
  break;
  case 3:
    $res = [];
    $brands_arr = [];

    $tipologie = get_terms( array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'parent' => $term->term_id,
        'fields' => 'slugs',
    ) );

    $brands = get_terms( array(
      'taxonomy' => 'lb-brand',
      'hide_empty' => false
    ) );

    foreach ($brands as $brand) {
      $the_query = new WP_Query( array(
        'post_type' => 'product',
        'tax_query' => array(
            'relation' => 'AND',
            array (
              'taxonomy' => 'product_cat',
              'field' => 'slug',
              'terms' => $tipologie,
            ),
            array (
              'taxonomy' => 'lb-brand',
              'field' => 'slug',
              'terms' => $brand->slug,
            )
          ),
      ));

      $brands_arr[] = $brand;

      $res_arr = [
        'brand' => $brand,
        'products' => $the_query->posts,
      ];
      $res[] = $res_arr;
    }

    wp_reset_postdata();

    $context = [
      'level' => 'esigenza',
      'data' => [
        'brand' => $brands_arr,
        'tipologie' => $tipologie,
        'results' => $res
      ],
    ];
    // echo '<pre>';
    // var_dump($context  );
    // die;
    Timber::render('@PathViews/woo/taxonomy-product-cat.twig', $context);
  break;
  case 4:
    echo "tipologia";
    $context = [
      'level' => 'tipologia',
      'data' => []
    ];
    Timber::render('@PathViews/woo/taxonomy-product-cat.twig', $context);
  break;
  default:
    echo "Level others";
}

