<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
$block_launch_two_cards = new BaseBlock($block);
$payload = [
    'cards' => [
        'images' =>[
            'original' => get_field('lb_block_launch_two_cards_image_left'),
            'large' => get_field('lb_block_launch_two_cards_image_left'),
            'medium' => get_field('lb_block_launch_two_cards_image_left'),
            'small' => get_field('lb_block_launch_two_cards_image_left')
        ],
        'infobox' => [
            'tagline' => get_field('lb_block_launch_two_cards_tagline_left'),
            'title' => get_field('lb_block_launch_two_cards_title_left'),
            'subtitle' => get_field('lb_block_launch_two_cards_subtitle_left'),
            'paragraph' => get_field('lb_block_launch_two_cards_paragraph_left'),
            'cta' => array_merge( get_field('lb_block_launch_two_cards_btn_left'),['variants' => [get_field('lb_block_launch_two_cards_btn_variants_left')]])
        ]
       
    ],
    [
        'images' =>[
            'original' => get_field('lb_block_launch_two_cards_image_right'),
            'large' => get_field('lb_block_launch_two_cards_image_right'),
            'medium' => get_field('lb_block_launch_two_cards_image_right'),
            'small' => get_field('lb_block_launch_two_cards_image_right')
        ],
        'infobox' => [
            'tagline' => get_field('lb_block_launch_two_cards_tagline_right'),
            'title' => get_field('lb_block_launch_two_cards_title_right'),
            'subtitle' => get_field('lb_block_launch_two_cards_subtitle_right'),
            'paragraph' => get_field('lb_block_launch_two_cards_paragraph_right'),
            'cta' => array_merge( get_field('lb_block_launch_two_cards_btn_right'),['variants' => [get_field('lb_block_launch_two_cards_btn_variants_right')]])
        ]
        
    ],
];
$block_launch_two_cards->setContext($payload);
$block_launch_two_cards->addInfobox();

$block_launch_two_cards->render();


/**
 *  launch two cards Block Template
 */
//??

// Carousel data






// Payload
// 'cardsLeft' => [
//     'original' => get_field('lb_block_launch_two_cards_image_left'),
//     'large' => get_field('lb_block_launch_two_cards_image_left'),
//     'medium' => get_field('lb_block_launch_two_cards_image_left'),
//     'small' => get_field('lb_block_launch_two_cards_image_left')
// ],
// 'cardsRight' => [
//     'original' => get_field('lb_block_launch_two_cards_image_right'),
//     'large' => get_field('lb_block_launch_two_cards_image_right'),
//     'medium' => get_field('lb_block_launch_two_cards_image_right'),
//     'small' => get_field('lb_block_launch_two_cards_image_right')
// ],
// 'infobox' => [
//     'side' => get_field('lb_block_launch_two_cards_side'),
//     'tagline' => get_field('lb_block_launch_two_cards_tagline'),
//     'title' => get_field('lb_block_launch_two_cards_title'),
//     'subtitle' => get_field('lb_block_launch_two_cards_subtitle'),
//     'paragraph' => get_field('lb_block_launch_two_cards_paragraph'),
//     'button' => get_field('lb_block_launch_two_cards_btn')
// ]

