<?php

/* Template Name: Template Archive Macro categoria */

use Caffeina\LaboSuisse\Resources\ArchiveMacro;
use Timber\Timber;

$archiveMacro = new ArchiveMacro($_GET['product_category']);

$context = [
    'brands_filter' => $archiveMacro->brands,
    'tipologie_filter' => [] ,
    'order_filter' => [],
    'grid_type' => 'ordered',
    'num_posts' => __('Risultati:', 'labo-suisse-theme') . ' <span>' . $archiveMacro->totalPosts . '</span>',
    'items' => $archiveMacro->products,
    'term_name' => $archiveMacro->category->name,
    'term_description' => $archiveMacro->category->description
];

Timber::render('@PathViews/template-archive-macro.twig', $context);

