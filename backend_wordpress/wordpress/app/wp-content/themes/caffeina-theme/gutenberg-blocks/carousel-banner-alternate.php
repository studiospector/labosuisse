

<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
$block_carousel_banner_alternate = new BaseBlock($block);
// Carousel data
// const dataLeftInfobox = {
//     slides: [
//         {
//             noContainer: true,
//             images: {
//                 original: '/assets/images/banner-img.jpg',
//                 large: '/assets/images/banner-img.jpg',
//                 medium: '/assets/images/banner-img.jpg',
//                 small: '/assets/images/banner-img.jpg'
//             },
//             infobox: {
//                 date: '00/00/00',
//                 subtitle: 'Lorem ipsum dolor sit amet, consectetur',
//                 paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
//                 cta: {
//                     url: '#',
//                     title: 'Scopri di più',
//                     variants: ['tertiary']
//                 },
//                 variants: ['alternate'],
//             },
//             variants: ['infobox-right', 'infobox-centered'],
//         },
//         {
//             noContainer: true,
//             images: {
//                 original: '/assets/images/banner-img.jpg',
//                 large: '/assets/images/banner-img.jpg',
//                 medium: '/assets/images/banner-img.jpg',
//                 small: '/assets/images/banner-img.jpg'
//             },
//             infobox: {
//                 date: '00/00/00',
//                 subtitle: 'Lorem ipsum dolor sit amet, consectetur',
//                 paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
//                 cta: {
//                     url: '#',
//                     title: 'Scopri di più',
//                     variants: ['tertiary']
//                 },
//                 variants: ['alternate'],
//             },
//             variants: ['infobox-right', 'infobox-centered'],
//         },
//     ]
// }
if( have_rows('lb_block_carousel_banner_alternate') ) {
    $slides = [];
    while( have_rows('lb_block_carousel_banner_alternate') ) : the_row();
        $slides[] = [
            'images' => [
                'original' => get_sub_field('lb_block_banner_alternate_img'),
                'large' => get_sub_field('lb_block_banner_alternate_img'),
                'medium' => get_sub_field('lb_block_banner_alternate_img'),
                'small' => get_sub_field('lb_block_banner_alternate_img')
            ],
            'noContainer' => true,
            'infobox' => [
                'tagline' => get_sub_field('lb_block_infobox_tagline'),
                'title' => get_sub_field('lb_block_infobox_title'),
                'subtitle' => get_sub_field('lb_block_infobox_subtitle'),
                'paragraph' => get_sub_field('lb_block_infobox_paragraph'),
                'cta' => array_merge( get_sub_field('lb_block_infobox_btn'),['variants' => [get_sub_field('lb_block_infobox_btn_variants')]])
            ],
            'variants' => [get_sub_field('lb_block_banner_alternate_variants_lr'),get_sub_field('lb_block_banner_alternate_variants_hcb')],
        ];
    endwhile;
}

$payload= [
    'slides' => $slides

];
// $this->context['data'] = array_merge($this->context['data'],$infobox);
$block_carousel_banner_alternate->setContext($payload);
$block_carousel_banner_alternate->render();




