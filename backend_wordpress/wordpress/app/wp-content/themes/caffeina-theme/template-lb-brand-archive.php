<?php

/* Template Name: Template Brands Archive */

$brands = [];
$brand_terms = lb_get_brands();

foreach ($brand_terms as $term) {
    $brands[] = [
        'images' => lb_get_images(get_field('lb_brand_image', $term)),
        'infobox' => [
            'subtitle' => $term->name,
            'paragraph' => $term->description,
            'cta' => [
                'url' => get_permalink( get_field('lb_brand_page', $term) ),
                'title' => __('Vai al brand', 'labo-suisse-theme'),
                'variants' => ['quaternary']
            ]
        ],
        'variants' => ['type-10']
    ];
}

$context = [
    'title' => get_the_title(),
    'content' => apply_filters( 'the_content', get_the_content() ),
    'items' => $brands,
];

Timber::render('@PathViews/template-lb-brand-archive.twig', $context);
