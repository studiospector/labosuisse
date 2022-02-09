<?php

$brand_obj = get_queried_object();
$brand_page = get_field('lb_brand_page', $brand_obj);

$context = [
    'brand' => $brand_obj,
    'content' => apply_filters( 'the_content', get_the_content(null, false, $brand_page) ),
];

Timber::render('@PathViews/taxonomy-lb-brand.twig', $context);
