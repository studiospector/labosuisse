<?php

/* Template Name: Template Distributori */

$curr_lang = lb_get_current_lang();

$brands = [];
foreach (lb_get_brands() as $brand) {
    $brands[] = [
        'value' => $brand->term_id,
        'label' => $brand->name,
    ];
}

$context = [
    'page_intro' => [
        'title' => get_the_title(),
        'description' => get_the_excerpt(),
    ],
    'filters' => [
        'filter_type' => 'map-distributor',
        'post_type' => 'lb-distributor',
        'posts_per_page' => -1,
        'containerized' => false,
        'items' => [
            [
                'id' => 'lb-brand',
                'label' => '',
                'placeholder' => __('Brand', 'labo-suisse-theme'),
                'multiple' => true,
                'required' => false,
                'disabled' => false,
                'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                'options' => $brands,
                'attributes' => ['data-taxonomy="lb-brand"'],
                'variants' => ['primary']
            ],
        ],
        'search' => null,
        'hidden' => null
    ],
    'map' => [
        'map_country' => strtoupper($curr_lang),
        'map_lang' => $curr_lang,
    ],
    'content' => apply_filters('the_content', get_the_content()),
];

Timber::render('@PathViews/template-distributor.twig', $context);
