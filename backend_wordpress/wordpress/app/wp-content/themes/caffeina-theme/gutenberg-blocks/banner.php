<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
$block_banner = new BaseBlock($block);

// const dataLeftInfoboxCTA = {
//     images: {
//         original: '/assets/images/banner-img.jpg',
//         large: '/assets/images/banner-img.jpg',
//         medium: '/assets/images/banner-img.jpg',
//         small: '/assets/images/banner-img.jpg'
//     },
//     infoboxTextAlignment: 'left', // left, right, center
//     infobox: {
//         tagline: 'LOREM IPSUM',
//         subtitle: 'Lorem ipsum dolor sit amet, consectetur',
//         paragraph: 'Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna. ',
//         cta: {
//             href: '#',
//             label: 'CALL TO ACTION',
//             variants: ['secondary']
//         }
//     },
//     variants: ['left'], // left, right, center
$payload = [
    'images' => [
        'original' => get_field('lb_block_banner_img'),
        'large' => get_field('lb_block_banner_img'),
        'medium' => get_field('lb_block_banner_img'),
        'small' => get_field('lb_block_banner_img')
    ],
    'infoboxTextAlignment' => get_field('lb_block_banner_infoboxtextalignment'),
    'variants' => get_field('lb_block_banner_variants'),

];

$block_banner->setContext($payload);

$block_banner->addInfobox();
$block_banner->render();
