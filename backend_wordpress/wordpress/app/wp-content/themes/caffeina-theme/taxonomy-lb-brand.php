<?php

$brand_obj = get_queried_object();
$level = get_category_parents_custom($brand_obj->term_id, 'lb-brand');

$items = [];

if ( have_posts() ) :
    $num_posts = 0;
    while ( have_posts() ) :
        the_post();

        $num_posts++;
        $items[] = [
            'product' => \Timber::get_post( get_the_ID() )
        ];

        if ($level == 1) {
            
        } else if ($level > 1) {
            
        }
    endwhile;
endif;

wp_reset_postdata();

$context = [
    'brand' => $brand_obj,
    'level' => $level,
    'num_posts' => __('Risultati:', 'labo-suisse-theme') . ' <span>' . $num_posts . '</span>',
    'items' => $items,
];

Timber::render('@PathViews/taxonomy-lb-brand.twig', $context);
