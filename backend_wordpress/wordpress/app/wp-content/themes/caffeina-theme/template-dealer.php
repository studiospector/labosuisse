<?php

/* Template Name: Template Concessionario */

$featured_image_id = get_post_thumbnail_id();

$context = [
    'hero' => !$featured_image_id ? null : [
        'images' => lb_get_images($featured_image_id),
        'container' => false,
        'variants' => ['small']
    ],
    'content' => apply_filters( 'the_content', get_the_content() ),
    'form_shortcode' => get_field('lb_template_dealer_form_shortcode')
];

Timber::render('@PathViews/template-dealer.twig', $context);
