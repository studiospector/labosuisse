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


function lb_get_job_location_options()
{
    return lb_get_job_options('lb-job-location');
}

function lb_get_job_department_options()
{
    return lb_get_job_options('lb-job-department');
}

function lb_get_job_options($type)
{
    $items = get_terms([
        'taxonomy' => $type,
        'hide_empty' => false,
    ]);

    $options = [];

    foreach ($items as $item) {
        $options[] = [
            'value' => $item->term_id,
            'label' => $item->name,
        ];
    }

    return $options;
}

function get_job_location()
{
    $isHeadquarter = false;
    $job_location_links = null;

    $job_location_terms = get_the_terms(get_the_ID(), 'lb-job-location');

    if ($job_location_terms && !is_wp_error($job_location_terms)) {
        $job_location_draught_links = [];

        foreach ($job_location_terms as $term) {
            if (get_field('lb_job_location_headquarter', $term)) {
                $isHeadquarter = true;
            }

            $job_location_draught_links[] = $term->name;
        }

        $job_location_links = join(", ", $job_location_draught_links);
    }

    return [
        'jobLocationLinks' => esc_html($job_location_links),
        'isHeadquarter' => $isHeadquarter
    ];
}


