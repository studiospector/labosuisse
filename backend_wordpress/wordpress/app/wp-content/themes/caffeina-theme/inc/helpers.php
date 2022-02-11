<?php

/**
 * Get all Brands
 */
function lb_get_brands()
{
    $brands = get_terms(array(
        'taxonomy' => 'lb-brand',
        'hide_empty' => false,
    ));

    $i = 0;
    foreach ($brands as $term) {
        if ($term->parent != 0) {
            unset($brands[$i]);
        }
        $i++;
    }

    return $brands;
}

/**
 * Get all "Linee di prodotto"
 */
function lb_get_brands_product_lines()
{
    $product_lines = get_terms(array(
        'taxonomy' => 'lb-brand',
        'hide_empty' => false,
    ));

    $i = 0;
    foreach ($product_lines as $term) {
        if ($term->parent == 0) {
            unset($product_lines[$i]);
        }
        $i++;
    }

    return $product_lines;
}

/**
 * Get taxonomy term level
 */
function get_category_parents_custom($category_id, $tax)
{
    $args = array(
        'separator' => ',',
        'link'      => false,
        'format'    => 'slug',
    );

    $parent_terms = get_term_parents_list($category_id, $tax, $args);

    return substr_count($parent_terms, ',');
}

function get_brands_menu()
{
    $payload =  get_terms(array(
        'taxonomy' => 'lb-brand',
        'hide_empty' => false,
        'parent' => null
    ));

    // die(json_encode($payload, JSON_PRETTY_PRINT));
}
