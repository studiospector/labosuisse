<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
$block_image_card = new BaseBlock($block);
$payload = [
    'images' => [
        'original' => get_field('lb_block_image_card_image_left'),
        'large' => get_field('lb_block_image_card_image_left'),
        'medium' => get_field('lb_block_image_card_image_left'),
        'small' => get_field('lb_block_image_card_image_left')
    ],
    'card' => [
        'images' =>[
            'original' => get_field('lb_block_image_card_image_right'),
            'large' => get_field('lb_block_image_card_image_right'),
            'medium' => get_field('lb_block_image_card_image_right'),
            'small' => get_field('lb_block_image_card_image_right')
        ],
        'variants' => ['type-8']

        
    ],
];
$block_image_card->addInfobox($payload['card']);
$block_image_card->setContext($payload);
$block_image_card->render();


/**
 *  launch two images Block Template
 */
//??

// Carousel data






// Payload
// 'imagesLeft' => [
//     'original' => get_field('lb_block_image_card_image_left'),
//     'large' => get_field('lb_block_image_card_image_left'),
//     'medium' => get_field('lb_block_image_card_image_left'),
//     'small' => get_field('lb_block_image_card_image_left')
// ],
// 'imagesRight' => [
//     'original' => get_field('lb_block_image_card_image_right'),
//     'large' => get_field('lb_block_image_card_image_right'),
//     'medium' => get_field('lb_block_image_card_image_right'),
//     'small' => get_field('lb_block_image_card_image_right')
// ],
// 'infobox' => [
//     'side' => get_field('lb_block_image_card_side'),
//     'tagline' => get_field('lb_block_image_card_tagline'),
//     'title' => get_field('lb_block_image_card_title'),
//     'subtitle' => get_field('lb_block_image_card_subtitle'),
//     'paragraph' => get_field('lb_block_image_card_paragraph'),
//     'button' => get_field('lb_block_image_card_btn')
// ]

