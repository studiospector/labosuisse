<?php

include get_template_directory() . '/functions/setup/composer-packages.php';

/**
 * Hero Block Template
 */

$id = 'hero-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

$className = 'hero';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Payload
$context = [
    'block' => 'carousel-hero',
    'data' => [
        'conf' => [
            'id' => esc_attr($id),
            'classes' => esc_attr($className)
        ],
        'slides' => [
            [
                'images' => [
                    'original' => '/assets/images/carousel-hero-img-2.jpg',
                    'large' => '/assets/images/carousel-hero-img-2.jpg',
                    'medium' => '/assets/images/carousel-hero-img-2.jpg',
                    'small' => '/assets/images/carousel-hero-img-2.jpg'
                ],
                'infobox' => [
                    'side' => get_field('lb_block_carousel_hero_side'),
                    'tagline' => get_field('lb_block_carousel_hero_tagline'),
                    'title' => get_field('lb_block_carousel_hero_title'),
                    'subtitle' => get_field('lb_block_carousel_hero_subtitle'),
                    'paragraph' => get_field('lb_block_carousel_hero_paragraph'),
                    'button' => get_field('lb_block_carousel_hero_btn'),
                    'buttonVariants' => get_field('lb_block_carousel_hero_btn_variants')
                ]
            ]
        ]
    ]
];

// Preview in editor
if ( isset($block['data']['is_preview']) && $block['data']['is_preview'] == true ) {
    echo $twig->render('gutenberg-preview.twig', [
        'base_url' => get_site_url(),
        'name' => 'Carousel Hero',
        'img' => 'block-two-images',
        'ext' => 'png',
    ]);
    return;
}

// Render component
echo $twig->render('./static/components/base/gutenberg-block-switcher.twig', $context);
