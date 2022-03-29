<?php

use Carbon\Carbon;

$page_for_posts = get_option('page_for_posts');

$items = [];

$args = [
    'post_status' => 'publish',
    'post_type' => 'post',
    's' => $_GET['s'] ?? null,
    'tax_query' => [],
    'date_query' => []
];

if(isset($_GET['type'])) {
    $args['tax_query'][] = [
        'taxonomy' => 'lb-post-typology',
        'field' => 'slug',
        'terms' => $_GET['type'],
    ];
}

if(isset($_GET['year'])) {
    $args['date_query'][] = [
        'year' => $_GET['year']
    ];
}

$posts = (new WP_Query($args))->get_posts();

foreach ($posts as $post) {
    $variant = 'type-2';
    $image = null;
    $cta_title = __("Leggi l'articolo", "labo-suisse-theme");

    $typology = get_field('lb_post_typology', $post->ID);
    if ($typology == 'press') {
        $variant = 'type-6';
        $image = get_field('lb_post_press_logo', $post->ID);
        $cta_title = __("Visualizza", "labo-suisse-theme");
    }

    $items[] = [
        'images' => lb_get_images(get_post_thumbnail_id($post->ID)),
        'date' => Carbon::createFromDate($post->post_date)->format('d/m/Y'),
        'variants' => [$variant],
        'infobox' => [
            'image' => $image,
            'subtitle' => $post->post_title,
            'paragraph' => $post->post_excerpt,
            'cta' => [
                'title' => $cta_title,
                'url' => get_permalink($post->ID),
                'iconEnd' => ['name' => 'arrow-right'],
                'variants' => ['quaternary']
            ]
        ],
    ];
}

wp_reset_postdata();

$context = [
    'title' => get_the_title($page_for_posts),
    'content' => apply_filters( 'the_content', get_the_content(null, false, $page_for_posts) ),
    'filters' => [
        'items' => [
            [
                'id' => 'lb-post-typology',
                'label' => '',
                'placeholder' => __('Tipologia', 'labo-suisse-theme'),
                'multiple' => true,
                'required' => false,
                'disabled' => false,
                'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                'options' => lb_get_post_typologies(),
                'variants' => ['primary']
            ],
            [
                'id' => 'lb-post-archive-years',
                'label' => '',
                'placeholder' => __('Riferimento anno', 'labo-suisse-theme'),
                'multiple' => true,
                'required' => false,
                'disabled' => false,
                'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                'options' => lb_get_posts_archive_years(),
                'variants' => ['primary']
            ],
        ],
        'search' => [
            'type' => 'search',
            'name' => 's',
            'label' => __('Cerca', 'labo-suisse-theme'),
            'value' => !empty($_GET['s']) ? $_GET['s'] : null,
            'disabled' => false,
            'required' => false,
            'buttonTypeNext' => 'submit',
            'variants' => ['secondary'],
        ],
    ],
    'posts' => $items,
    'pagination' => lb_pagination(),
];

Timber::render('@PathViews/home.twig', $context);
