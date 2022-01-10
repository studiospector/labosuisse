<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
$block_banner_alternate = new BaseBlock($block);

// const dataRightInfoboxFullHeight = {
//     images: {
//         original: '/assets/images/banner-img.jpg',
//         large: '/assets/images/banner-img.jpg',
//         medium: '/assets/images/banner-img.jpg',
//         small: '/assets/images/banner-img.jpg'   
//     },
//     infobox: {
//         tagline: 'LOREM IPSUM',
//         subtitle: 'Lorem ipsum dolor sit amet, consectetur',
//         paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
//         cta: {
//             url: '#',
//             title: 'Scopri di più',
//             iconEnd: { name: 'arrow-right' },
//             variants: ['quaternary']
//         },
//         variants: ['alternate'],
//     },
//     imageBig: false,
//     variants: ['infobox-right', 'infobox-fullheight'], // infobox-left, infobox-right AND infobox-fullheight, infobox-centered, infobox-bottom
$payload = [
    'images' => [
        'original' => get_field('lb_block_banner_alternate_img'),
        'large' => get_field('lb_block_banner_alternate_img'),
        'medium' => get_field('lb_block_banner_alternate_img'),
        'small' => get_field('lb_block_banner_alternate_img')
    ],
   'imageBig': => get_field('lb_block_banner_alternate_img_big'),
   'variants' => [get_field('lb_block_banner_alternate_variants_lr'),get_field('lb_block_banner_alternate_variants_hcb')],

];

$block_banner_alternate->setContext($payload);

$block_banner_alternate->addInfobox();
$block_banner_alternate->render();
