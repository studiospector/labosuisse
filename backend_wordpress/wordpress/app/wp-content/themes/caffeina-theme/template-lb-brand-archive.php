<?php

/* Template Name: Template Brands Archive */

$brands = [];
$brand_terms = lb_get_brands();

foreach ($brand_terms as $term) {
    $brand_page = get_field('lb_brand_page', $term);
    $brands[] = [
        'images' => lb_get_images(get_field('lb_brand_image', $term)),
        'infobox' => [
            'subtitle' => $term->name,
            'paragraph' => $term->description,
            'cta' => !empty($brand_page) ? [
                'url' => get_permalink($brand_page),
                'title' => __('Vai al brand', 'labo-suisse-theme'),
                'variants' => ['quaternary']
            ] : null
        ],
        'variants' => ['type-10']
    ];
}

$context = [
    'title' => get_the_title(),
    'content' => apply_filters( 'the_content', get_the_content() ),
    'filters' => [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'containerized' => false,
        'items' => [
            [
                'id' => 'lb-product-cat-zona',
                'label' => '',
                'placeholder' => __('Zona', 'labo-suisse-theme'),
                'multiple' => false,
                'required' => false,
                'disabled' => false,
                'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                'options' => [],
                'attributes' => ['data-taxonomy="product_cat"'],
                'variants' => ['primary']
            ],
            [
                'id' => 'lb-product-cat-esigenza',
                'label' => '',
                'placeholder' => __('Esigenza', 'labo-suisse-theme'),
                'multiple' => false,
                'required' => false,
                'disabled' => false,
                'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                'options' => [],
                'attributes' => ['data-taxonomy="product_cat"'],
                'variants' => ['primary']
            ],
        ],
        'search' => [
            'type' => 'search',
            'name' => 's',
            'label' => __('Cerca', 'labo-suisse-theme'),
            'value' => !empty($_GET['s']) ? $_GET['s'] : null,
            'disabled' => false,
            'required' => true,
            'buttonTypeNext' => 'submit',
            'variants' => ['secondary'],
        ],
    ],
    'items' => $brands,
];

Timber::render('@PathViews/template-lb-brand-archive.twig', $context);
