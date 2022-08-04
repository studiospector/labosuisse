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
        'buttonVariantNext' => 'primary',
        'class' => 'js-geolocation-render-element',
        'variants' => ['secondary'],
    ],
    'card_link_label' => __('Vai alle indicazioni', 'labo-suisse-theme'),
    'num_posts_label' => __('Risultati:', 'labo-suisse-theme'),
    'num_posts' => $items['count'],
    'form_action' => get_post_type_archive_link('lb-beauty-specialist') . '#lb-form-container',
    'items' => $items['items'],
    'no_results' => [
        'title' => __('Nessun evento previsto<br>questo mese nella tua provincia.', 'labo-suisse-theme'),
        'text' => __('Cerca un’altra città a te vicina per scoprire le date a calendario.', 'labo-suisse-theme'),
    ],
];

Timber::render('@PathViews/archive-lb-beauty-specialist.twig', $context);
