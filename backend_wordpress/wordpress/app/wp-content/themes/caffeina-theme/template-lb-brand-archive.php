<?php

/* Template Name: Template Brands Archive */

$brands = [];

$brand_terms = lb_get_brands([
    'name__like' => !empty($_GET['search']) ? $_GET['search'] : null,
]);

$areas = [];
$needs = [];

foreach (get_all_macro() as $macro) {
    foreach (get_all_area($macro) as $area) {
        $areas[] = [
            'value' => $area->term_id,
            'label' => $area->name
        ];

        foreach (get_all_needs($area) as $need) {
            $needs[] = [
                'value' => $need->term_id,
                'label' => $need->name
            ];
        }
    }
}



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
        'type' => 'type-10',
        'variants' => null
    ];
}

$context = [
    'title' => get_the_title(),
    'content' => apply_filters( 'the_content', get_the_content() ),
    'filters' => [
        'post_type' => 'brand',
        'posts_per_page' => -1,
        'containerized' => false,
        'items' => [
            [
                'id' => 'lb-product-cat-zona',
                'label' => '',
                'placeholder' => __('Zona', 'labo-suisse-theme'),
                'multiple' => true,
                'required' => false,
                'disabled' => false,
                'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                'options' => $areas,
                'attributes' => ['data-taxonomy="product_cat"'],
                'variants' => ['primary']
            ],
            [
                'id' => 'lb-product-cat-esigenza',
                'label' => '',
                'placeholder' => __('Esigenza', 'labo-suisse-theme'),
                'multiple' => true,
                'required' => false,
                'disabled' => false,
                'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                'options' => $needs,
                'attributes' => ['data-taxonomy="product_cat"'],
                'variants' => ['primary']
            ],
        ],
        'search' => [
            'type' => 'search',
            'name' => 'search',
            'label' => __('Cerca', 'labo-suisse-theme'),
            'value' => !empty($_GET['search']) ? $_GET['search'] : null,
            'disabled' => false,
            'required' => true,
            'buttonTypeNext' => 'submit',
            'variants' => ['secondary'],
        ],
    ],
    'items' => $brands,
];

Timber::render('@PathViews/template-lb-brand-archive.twig', $context);
