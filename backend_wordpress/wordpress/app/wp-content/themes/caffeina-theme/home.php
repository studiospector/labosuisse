<?php

$page_for_posts = get_option('page_for_posts');

$items = [];

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

        $variant = 'type-2';
        $image = null;
        $cta_title = __("Leggi l'articolo","labo-suisse-theme");

        $typology = get_field('lb_post_typology');
        if ($typology == 'press') {
            $variant = 'type-6';
            $image = get_field('lb_post_press_logo');
            $cta_title = __("Visualizza","labo-suisse-theme");
        }

        $items[] = [
            'images' => [
                'original' => wp_get_attachment_url(get_post_thumbnail_id( get_the_ID() )),
                'large' => wp_get_attachment_url(get_post_thumbnail_id( get_the_ID() )),
                'medium' => wp_get_attachment_url(get_post_thumbnail_id( get_the_ID() )),
                'small' => wp_get_attachment_url(get_post_thumbnail_id( get_the_ID() ))
            ],
            'date' => get_the_date("d/m/Y"),
            'variants' => [$variant],
            'infobox' => [
                'image' => $image,
                'subtitle' => get_the_title(),
                'paragraph' => get_the_excerpt(),
                'cta' => [
                    'title' => $cta_title,
                    'url' => get_permalink( get_the_ID() ),
                    'iconEnd' => ['name' => 'arrow-right'],
                    'variants' => ['quaternary']
                ]
            ],
        ];
    endwhile;
endif;

wp_reset_postdata();

$context = [
    'title' => get_the_title($page_for_posts),
    'content' => apply_filters( 'the_content', get_the_content(null, false, $page_for_posts) ),
    'postTypologiesTax' => lb_get_post_typologies(),
    'years' => lb_get_posts_archive_years(),
    'posts' => $items,
    'pagination' => lb_pagination(),
];

Timber::render('@PathViews/home.twig', $context);
