<?php

/**
 * The search form template
 * 
 * @package Abbrivio
 */

$context = [
    'id' => 'lb-search-val', // Generate a unique ID for each form
    'action' => get_permalink(get_field('lb_page_search', 'option')),
    'search_val' => !empty($_GET['lb-search-val']) ? esc_attr($_GET['lb-search-val']) : null,
    'label' => __('Cerca un prodotto, una linea...', 'labo-suisse-theme'),
    'label_res_less' => __('risultato per', 'labo-suisse-theme'),
    'label_res_more' => __('risultati per', 'labo-suisse-theme'),
];

Timber::render('@PathViews/components/searchform.twig', $context);
