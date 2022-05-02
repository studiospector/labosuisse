<?php

/* Template Name: Template Ricerca */

use Caffeina\LaboSuisse\Api\GlobalSearch\Search;

$search_val = !empty($_GET['lb-search-val']) ? $_GET['lb-search-val'] : '';

$items = (new Search())
    ->setSearch($search_val)
    ->get();

$context['items'] = $items;

Timber::render('@PathViews/search.twig', $context);
