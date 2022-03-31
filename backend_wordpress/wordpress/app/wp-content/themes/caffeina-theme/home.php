<?php

$page_for_posts = get_option('page_for_posts');

$items = [];

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

        $variant = 'type-2';
        $image = null;
        $cta_title = __("Leggi l'articolo", 'labo-suisse-theme');

        $typology = get_field('lb_post_typology');
        if ($typology == 'press') {
            $variant = 'type-6';
            $image = get_field('lb_post_press_logo');
            $cta_title = __('Visualizza', 'labo-suisse-theme');
        }

        $items[] = [
            'images' => lb_get_images(get_post_thumbnail_id(get_the_ID())),
            'date' => get_the_date('d/m/Y'),
            'variants' => [$variant],
            'infobox' => [
                'image' => $image,
                'subtitle' => get_the_title(),
                'paragraph' => get_the_excerpt(),
                'cta' => [
                    'title' => $cta_title,
                    'url' => get_permalink( get_the_ID() ),
                    'iconEnd' => ['name' => 'arrow-right'],
                    'variants' => ['quaternary']
                ]
            ],
        ];
    endwhile;
endif;

wp_reset_postdata();

$context = [
    'title' => get_the_title($page_for_posts),
    'content' => apply_filters( 'the_content', get_the_content(null, false, $page_for_posts) ),
    'filters' => [
        'post_type' => 'post',
        'posts_per_page' => 4,
        'containerized' => false,
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
                'attributes' => ['data-taxonomy="lb-post-typology"'],
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
                'attributes' => ['data-year="lb-post-archive-years"'],
                'variants' => ['primary']
            ],
        ],
        'search' => [
            'type' => 'search',
            'name' => 's',
            'label' => __('Cerca... (inserisci almeno 3 caratteri)', 'labo-suisse-theme'),
            'value' => !empty($_GET['s']) ? $_GET['s'] : null,
            'disabled' => false,
            'required' => true,
            'buttonTypeNext' => 'submit',
            'variants' => ['secondary'],
        ],
    ],
    'posts' => $items,
    'pagination' => lb_pagination(),
    'load_more' => [
        'posts_per_page' => 4,
        'label' => __('Carica altro', 'labo-suisse-theme'),
    ],
];

Timber::render('@PathViews/home.twig', $context);
