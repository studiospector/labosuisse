<?php

$featured_images = lb_get_images(get_post_thumbnail_id(get_the_ID()));
$reading_time = get_field('lb_post_reading_time');

$context = [
    'featured_image' => ($featured_images) ? [
        'images' => $featured_images,
        'container' => false,
        'variants' => ['small']
    ] : null,
    'page_intro' => [
        'tagline' => __('Articolo', 'labo-suisse-theme'),
        'title' => get_the_title(),
    ],
    'date' => (get_field('lb_post_creation_date_show') !== false) ? get_the_date() : null,
    'reading_time' => ($reading_time) ? $reading_time . ' ' . __('min', 'labo-suisse-theme') : null,
    'content' => apply_filters( 'the_content', get_the_content() ),
];

Timber::render('@PathViews/single.twig', $context);
