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

$context = [
    'conf' => [
        'id' => esc_attr($id),
        'classes' => esc_attr($className)
    ],
    'img' => get_field('cf_block_hero_img'),
    'text' => get_field('cf_block_hero_text'),
    'button' => get_field('cf_block_hero_button'),
    'bgColor' => get_field('cf_block_hero_background')
];

echo $twig->render('/static/components/hero.twig', $context);
