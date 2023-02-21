<?php

use Caffeina\LaboSuisse\Option\Option;
use Timber\Timber;

$options = (new Option())->getStoresOptions();

$curr_lang = lb_get_current_lang();

$context = [
    'page_intro' => [
        'title' => $options['title'],
        'description' => $options['description'],
    ],
    'store_locator' => [
        'map_country' => 'IT',
        'map_lang' => $curr_lang,
        'not_found' => sprintf(__('Nessuna farmacia trovata. %s Naviga la mappa per trovare i punti vendita autorizzati Labo più vicini.', 'labo-suisse-theme'), '<br>'),
        'search' => [
            'type' => 'search',
            'name' => "lb-search-autocomplete",
            'label' => __('Inserisci città, provincia, CAP', 'labo-suisse-theme'),
            'value' => $_GET['lb-search-val'] ?? null,
            'disabled' => false,
            'required' => false,
            'autocomplete' => 'off',
            'buttonTypeNext' => 'button',
            'buttonVariantNext' => 'primary',
            'class' => 'js-caffeina-sl-search',
            'variants' => ['secondary'],
        ]
    ],
    'card' => $options['card'],
];

Timber::render('@PathViews/archive-lb-store.twig', $context);
