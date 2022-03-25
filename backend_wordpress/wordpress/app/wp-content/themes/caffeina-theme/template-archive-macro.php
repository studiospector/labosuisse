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
    'block_announcements' => (!get_field('lb_product_cat_announcements_state', $term)) ? null : [
        'infobox' => [
            'title' => get_field('lb_product_cat_announcements_title', $term),
        ],
        'cards' => [
            [
                'images' => lb_get_images(get_field('lb_product_cat_announcements_cardleft_image', $term)),
                'infobox' => getCardByType(get_field('lb_product_cat_announcements_cardleft_item_type', $term), 'left', $term)
            ],
            [
                'images' => lb_get_images(get_field('lb_product_cat_announcements_cardright_image', $term)),
                'infobox' => getCardByType(get_field('lb_product_cat_announcements_cardright_item_type', $term), 'right', $term)
            ]
        ],
        'variants' => ['horizontal'],
    ]
];

Timber::render('@PathViews/template-archive-macro.twig', $context);

