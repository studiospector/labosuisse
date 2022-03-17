<?php

use Timber\Timber;

$page_archive = get_field('lb_archive_beauty_specialist_page', 'option');

$items = (new \Caffeina\LaboSuisse\Resources\BeautySpecialist())
    ->all();

$context = [
    'content' => apply_filters('the_content', $page_archive->post_content),
    'search' => [
        'type' => 'search',
        'name' => "city",
        'label' => "Inserisci provincia",
        'disabled' => false,
        'required' => false,
        'autocomplete' => 'off',
        'variants' => ['tertiary'],
    ],
    'num_posts' => $items['count'],
    'items' => $items['items'],
];

Timber::render('@PathViews/archive-lb-beauty-specialist.twig', $context);
