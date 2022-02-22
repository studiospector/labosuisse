<?php

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

$context = [
    'image' => $image,
    'title' => ($image) ? null : get_the_title(),
    'description' => get_the_excerpt(),
    'faq' => [
        'items' => $items
    ],
    'infobox' => [
        'subtitle' => 'Hai altri dubbi?',
        'paragraph' => 'Contatta un esperto Labo e ut perspiciatis unde omnis<br>iste natus error sit voluptatem accusantium doloremque.',
        'cta' => [
            'url' => '#',
            'title' => 'Contatta un esperto',
            'variants' => ['tertiary']
        ],
    ]
];

Timber::render('@PathViews/single-lb-faq.twig', $context);
