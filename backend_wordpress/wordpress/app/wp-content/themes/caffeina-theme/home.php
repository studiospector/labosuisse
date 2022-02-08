<?php

$page_for_posts = get_option('page_for_posts');

if ( !isset( $paged ) || !$paged ) {
    $paged = 1;
}

$args = array(
    'posts_per_page' => 10,
    'paged' => $paged,
    'post_type' => get_post_type(),
    // 'orderby'  => array('meta_value' => 'DESC'),
);

$posts = get_posts();
$items = [];

foreach ($posts as $item) {
    $items[] = [
        'images' => [
            'original' => wp_get_attachment_url(get_post_thumbnail_id($item->ID)),
            'large' => wp_get_attachment_url(get_post_thumbnail_id($item->ID)),
            'medium' => wp_get_attachment_url(get_post_thumbnail_id($item->ID)),
            'small' => wp_get_attachment_url(get_post_thumbnail_id($item->ID))
        ],
        'date' => date("d/m/Y", strtotime($item->post_date)),
        'variants' => ['type-2'],
        'infobox' => [
            'subtitle' => $item->post_title,
            'paragraph' => $item->post_excerpt,
            'cta' => [
                'title' => __("Leggi l'articolo","labo-suisse-theme"),
                'url' => get_permalink($item->ID),
                'iconEnd' => ['name' => 'arrow-right'],
                'variants' => ['quaternary']
            ]
        ],
    ];
}

$context = [
    'title' => get_the_title($page_for_posts),
    'content' => apply_filters( 'the_content', get_the_content(null, false, $page_for_posts) ),
    'postTypologiesTax' => lb_get_post_typologies(),
    'years' => lb_get_posts_archive_years(),
    'posts' => $items,
];

// echo '<pre>';
// var_dump( $context['years'] );
// die;

Timber::render('@PathViews/home.twig', $context);
