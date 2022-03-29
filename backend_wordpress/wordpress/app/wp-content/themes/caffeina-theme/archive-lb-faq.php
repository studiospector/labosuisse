<?php

use Caffeina\LaboSuisse\Option\Option;

$items = [];

if (have_posts()) :
    while (have_posts()) :
        the_post();

        $image = (get_field('lb_faq_logo')) ? get_field('lb_faq_logo') : null;

        $questions = [];

        while (have_rows('lb_faq_items')) {
            the_row();
            $questions[] = get_sub_field('lb_faq_item_question');
        }

        $questions = array_slice($questions, -4);

        $items[] = [
            'images' => lb_get_images(get_post_thumbnail_id(get_the_ID())),
            'infobox' => [
                'image' => $image,
                'subtitle' => ($image) ? null : get_the_title(),
                'items' => $questions,
                'cta' => [
                    'title' => __('Vedi tutto', 'labo-suisse-theme'),
                    'url' => get_permalink(get_the_ID()),
                    'variants' => ['quaternary']
                ]
            ],
            'variants' => ['type-7'],
        ];
    endwhile;
endif;

wp_reset_postdata();

$options = (new Option())
    ->getFaqOptions();

$context = [
    'title' => $options['title'],
    'description' => $options['description'],
    'filters' => [
        'post_type' => 'lb-faq',
        'containerized' => false,
        'items' => [],
        'search' => [
            'type' => 'search',
            'name' => 's',
            'label' => __('Cerca un argomento, prodotto, linea...', 'labo-suisse-theme'),
            'value' => !empty($_GET['s']) ? $_GET['s'] : null,
            'disabled' => false,
            'required' => true,
            'buttonTypeNext' => 'submit',
            'variants' => ['secondary'],
        ],
    ],
    'items' => $items,
];

Timber::render('@PathViews/archive-lb-faq.twig', $context);
