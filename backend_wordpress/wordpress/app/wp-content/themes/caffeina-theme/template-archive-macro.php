<?php

/* Template Name: Template Archive Macro categoria */

use Caffeina\LaboSuisse\Resources\ArchiveMacro;
use Timber\Timber;

$archiveMacro = new ArchiveMacro($_GET['product_category']);

$context = [
    'filters' => [
        'items' => [
            [
                'id' => 'lb-product-brands',
                'label' => '',
                'placeholder' => __('Brand', 'labo-suisse-theme'),
                'multiple' => true,
                'required' => false,
                'disabled' => false,
                'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                'options' => $archiveMacro->brands,
                'variants' => ['primary']
            ],
            [
                'id' => 'lb-product-cat-typology',
                'label' => '',
                'placeholder' => __('Tipi di prodotto', 'labo-suisse-theme'),
                'multiple' => true,
                'required' => false,
                'disabled' => false,
                'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                'options' => [],
                'variants' => ['primary']
            ],
            [
                'id' => 'lb-product-order',
                'label' => __('Ordina per', 'labo-suisse-theme'),
                'placeholder' => __('Scegli...', 'labo-suisse-theme'),
                'multiple' => true,
                'required' => false,
                'disabled' => false,
                'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                'options' => [],
                'variants' => ['secondary']
            ],
        ],
        'search' => [
            'type' => 'search',
            'name' => 'lb-product-search',
            'label' => __('Cerca', 'labo-suisse-theme'),
            'disabled' => false,
            'required' => false,
            'variants' => ['secondary'],
        ],
    ],
    'grid_type' => 'ordered',
    'num_posts' => __('Risultati:', 'labo-suisse-theme') . ' <span>' . $archiveMacro->totalPosts . '</span>',
    'items' => $archiveMacro->products,
    'term_name' => $archiveMacro->category->name,
    'term_description' => $archiveMacro->category->description
];

Timber::render('@PathViews/template-archive-macro.twig', $context);

