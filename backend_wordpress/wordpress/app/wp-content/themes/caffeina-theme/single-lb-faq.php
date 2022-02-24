<?php

use Caffeina\LaboSwiss\Option\Option;

$image = (get_field('lb_faq_logo')) ? get_field('lb_faq_logo') : null;

$items = [];
if (have_rows('lb_faq_items')) {
    while (have_rows('lb_faq_items')) : the_row();
        $items[] = [
            'title' => get_sub_field('lb_faq_item_question'),
            'content' => get_sub_field('lb_faq_item_answer')
        ];
    endwhile;
}

$options = (new Option())->getFaqOptions();

$context = [
    'image' => $image,
    'title' => ($image) ? null : get_the_title(),
    'description' => get_the_excerpt(),
    'faq' => [
        'items' => $items
    ],
    'infobox' => [
        'subtitle' => $options['infobox']['title'],
        'paragraph' => $options['infobox']['paragraph'],
        'cta' => [
            'url' => $options['infobox']['cta']['url'],
            'title' => $options['infobox']['cta']['title'],
            'variants' => ['tertiary']
        ],
    ]
];

Timber::render('@PathViews/single-lb-faq.twig', $context);
