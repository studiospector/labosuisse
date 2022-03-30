<?php

$brand_obj = get_queried_object();
$level = get_category_parents_custom($brand_obj->term_id, 'lb-brand');

$items = [];
$grid_type = 'default';

if ( have_posts() ) :
    $num_posts = 0;
    while ( have_posts() ) :
        the_post();

        $num_posts++;

        if ($level == 1) {
            $grid_type = 'default';

            $terms = get_the_terms( get_the_ID(), 'lb-brand' );
            
            foreach ($terms as $term) {
                if ( $term->parent != 0 ) {
                    $items[] = \Timber::get_post( get_the_ID() );
                }
            }

        } else if ($level > 1) {
            $items[] = \Timber::get_post( get_the_ID() );
        }
    endwhile;
endif;

wp_reset_postdata();

$context = [
    'brand' => $brand_obj,
    'level' => $level,
    'num_posts' => __('Risultati:', 'labo-suisse-theme') . ' <span>' . $num_posts . '</span>',
    'grid_type' => $grid_type,
    'items' => $items,
    'pagination' => lb_pagination(),
];

Timber::render('@PathViews/taxonomy-lb-brand.twig', $context);
