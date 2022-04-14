<?php

/* Template Name: Template Distributori */

$context = [
    'page_intro' => [
        'title' => get_the_title(),
        'description' => get_the_excerpt(),
    ],
    'filters' => [
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
                'options' => [],
                'attributes' => ['data-taxonomy="lb-brand"'],
                'variants' => ['primary']
            ],
        ],
        'search' => null,
        'hidden' => null
    ],
    'content' => apply_filters('the_content', get_the_content()),
];

Timber::render('@PathViews/template-distributor.twig', $context);
