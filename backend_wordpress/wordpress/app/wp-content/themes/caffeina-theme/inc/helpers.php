<?php

/**
 * Get Images array
 */
function lb_get_images($id)
{
    return ($id) ? [
        'original' => wp_get_attachment_url($id),
        'lg' => wp_get_attachment_image_src($id, 'lb-img-size-lg')[0],
        'md' => wp_get_attachment_image_src($id, 'lb-img-size-md')[0],
        'sm' => wp_get_attachment_image_src($id, 'lb-img-size-sm')[0],
        'xs' => wp_get_attachment_image_src($id, 'lb-img-size-xs')[0]
    ] : null;
}

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
