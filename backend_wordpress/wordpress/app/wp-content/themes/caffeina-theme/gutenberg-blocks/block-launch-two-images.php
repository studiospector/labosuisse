<?php
require_once(__DIR__.'/baseBlock.php');
include get_template_directory() . '/inc/composer-packages.php';
use gutenbergBlocks\BaseBlock;
$block_launch_two_images = new BaseBlock($block,$twig);
$payload = [
    'imagesLeft' => [
        'original' => get_field('lb_block_launch_two_images_image_left'),
        'large' => get_field('lb_block_launch_two_images_image_left'),
        'medium' => get_field('lb_block_launch_two_images_image_left'),
        'small' => get_field('lb_block_launch_two_images_image_left')
    ],
    'imagesRight' => [
        'original' => get_field('lb_block_launch_two_images_image_right'),
        'large' => get_field('lb_block_launch_two_images_image_right'),
        'medium' => get_field('lb_block_launch_two_images_image_right'),
        'small' => get_field('lb_block_launch_two_images_image_right')
    ],
    'infobox' => [
        'side' => get_field('lb_block_launch_two_images_side'),
        'tagline' => get_field('lb_block_launch_two_images_tagline'),
        'title' => get_field('lb_block_launch_two_images_title'),
        'subtitle' => get_field('lb_block_launch_two_images_subtitle'),
        'paragraph' => get_field('lb_block_launch_two_images_paragraph'),
        'button' => get_field('lb_block_launch_two_images_btn')
    ]
];
$block_launch_two_images->setContext($payload);
$block_launch_two_images->render();


/**
 *  launch two images Block Template
 */
//??

// Carousel data






// Payload
// 'imagesLeft' => [
//     'original' => get_field('lb_block_launch_two_images_image_left'),
//     'large' => get_field('lb_block_launch_two_images_image_left'),
//     'medium' => get_field('lb_block_launch_two_images_image_left'),
//     'small' => get_field('lb_block_launch_two_images_image_left')
// ],
// 'imagesRight' => [
//     'original' => get_field('lb_block_launch_two_images_image_right'),
//     'large' => get_field('lb_block_launch_two_images_image_right'),
//     'medium' => get_field('lb_block_launch_two_images_image_right'),
//     'small' => get_field('lb_block_launch_two_images_image_right')
// ],
// 'infobox' => [
//     'side' => get_field('lb_block_launch_two_images_side'),
//     'tagline' => get_field('lb_block_launch_two_images_tagline'),
//     'title' => get_field('lb_block_launch_two_images_title'),
//     'subtitle' => get_field('lb_block_launch_two_images_subtitle'),
//     'paragraph' => get_field('lb_block_launch_two_images_paragraph'),
//     'button' => get_field('lb_block_launch_two_images_btn')
// ]

