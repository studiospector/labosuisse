<?php

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

// Carousel data
if( have_rows('lb_block_carousel_hero') ) {
    $slides = [];
    while( have_rows('lb_block_carousel_hero') ) : the_row();
        $slides[] = [
            'images' => [
                'original' => get_sub_field('lb_block_carousel_hero_img'),
                'large' => get_sub_field('lb_block_carousel_hero_img'),
                'medium' => get_sub_field('lb_block_carousel_hero_img'),
                'small' => get_sub_field('lb_block_carousel_hero_img')
            ],
            'infobox' => [
                'side' => get_sub_field('lb_block_carousel_hero_side'),
                'tagline' => get_sub_field('lb_block_carousel_hero_tagline'),
                'title' => get_sub_field('lb_block_carousel_hero_title'),
                'subtitle' => get_sub_field('lb_block_carousel_hero_subtitle'),
                'paragraph' => get_sub_field('lb_block_carousel_hero_paragraph'),
                'button' => get_sub_field('lb_block_carousel_hero_btn'),
                'buttonVariants' => get_sub_field('lb_block_carousel_hero_btn_variants')
            ]
        ];
    endwhile;
}
$payload= [
    'slides' => $slides

];
// $this->context['data'] = array_merge($this->context['data'],$infobox);
$block_carousel_hero->setContext($payload);
$block_carousel_hero->render();

// Preview in editor
if ( isset($block['data']['is_preview']) && $block['data']['is_preview'] == true ) {
    Timber::render('@PathViews/gutenberg-preview.twig', [
        'base_url' => get_site_url(),
        'name' => 'Carousel Hero',
        'img' => 'block-two-images',
        'ext' => 'png',
    ]);
    return;
}

// Render component
Timber::render('@PathViews/components/base/gutenberg-block-switcher.twig', $context);
