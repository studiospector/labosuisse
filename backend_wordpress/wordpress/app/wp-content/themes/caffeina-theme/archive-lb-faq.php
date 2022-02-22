<?php

$items = [];

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

        $image = (get_field('lb_faq_logo')) ? get_field('lb_faq_logo') : null;

        $items[] = [
            'images' => lb_get_images(get_post_thumbnail_id(get_the_ID())),
            'infobox' => [
                'image' => $image,
                'subtitle' => ($image) ? null : get_the_title(),
                'items' => [
                    'Il trattamento Fillerina può essere utilizzato anche per le rughe del labbro superiore della bocca?',
                    'Si possono effettuare pulizia viso o lampade solari durante il trattamento?',
                    'Ci si può esporre al sole durante il trattamento?',
                    'Si può utilizzare Fillerina nei periodi di gravidanza o in allattamento?',
                ],
                'cta' => [
                    'title' => __('Vedi tutto', 'labo-suisse-theme'),
                    'url' => get_permalink( get_the_ID() ),
                    'variants' => ['quaternary']
                ]
            ],
            'variants' => ['type-7'],
        ];
    endwhile;
endif;

wp_reset_postdata();

$context = [
    'title' => 'Le domande frequenti',
    'description' => 'Sfoglia tra le categorie di domande o cerca il tuo<br>argomento per trovare la soluzione più adatta a te.',
    'items' => $items,
];

Timber::render('@PathViews/archive-lb-faq.twig', $context);
