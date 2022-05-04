<?php

/* Template Name: Template Ricerca */

use Caffeina\LaboSuisse\Api\GlobalSearch\Search;

$search_val = !empty($_GET['lb-search-val']) ? $_GET['lb-search-val'] : null;

$search = (new Search())
    ->setSearch($search_val)
    ->get();

$context = [
    'search_val' => $search_val,
    'num_res' => $search['count'],
    'items' => $search['items'],
    'res_text' => __("Risultati della ricerca", 'labo-suisse-theme'),
    'base_text' => [
        'title' => __('Ricerca globale', 'labo-suisse-theme'),
        'paragraph' => __('Cerca qualunque contenuto nel sito.', 'labo-suisse-theme')
    ],
    'no_results_tab' => [
        'paragraph' => __('Siamo spiacenti! nessun risultato corrisponde alla tua ricerca per quasta entità.', 'labo-suisse-theme')
    ],
    'no_results' => [
        'title' => sprintf(__("La tua ricerca per &ldquo;%s&rdquo; non ha prodotto alcun risultato", 'labo-suisse-theme'), $search_val),
        'paragraph' => __('Prova a migliorare la tua ricerca controllando eventuali errori di digitazione o cercando un termine più generico.', 'labo-suisse-theme')
    ]
];

Timber::render('@PathViews/search.twig', $context);
