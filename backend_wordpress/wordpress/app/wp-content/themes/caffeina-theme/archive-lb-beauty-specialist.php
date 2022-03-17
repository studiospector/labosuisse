<?php

use Timber\Timber;

$page_archive = get_field('lb_archive_beauty_specialist_page', 'option');

$items = (new \Caffeina\LaboSuisse\Resources\BeautySpecialist())
    ->all();

$context = [
    'content' => apply_filters('the_content', $page_archive->post_content),
    'search' => [
        'type' => 'search',
        'name' => 'city',
        'label' => __('Inserisci provincia', 'labo-suisse-theme'),
        'value' => !empty($_GET['city']) ? $_GET['city'] : null,
        'disabled' => false,
        'required' => false,
        'autocomplete' => 'off',
        'buttonTypeNext' => 'submit',
        'class' => 'js-geolocation-render-element',
        'variants' => ['tertiary'],
    ],
    'card_link_label' => __('Vai alle indicazioni', 'labo-suisse-theme'),
    'num_posts_label' => __('Risultati:', 'labo-suisse-theme'),
    'num_posts' => $items['count'],
    'items' => $items['items'],
];

Timber::render('@PathViews/archive-lb-beauty-specialist.twig', $context);
