

<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
$block_carousel_hero = new BaseBlock($block);
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
                'side' => get_sub_field('lb_block_infobox_side'),
                'tagline' => get_sub_field('lb_block_infobox_tagline'),
                'title' => get_sub_field('lb_block_infobox_title'),
                'subtitle' => get_sub_field('lb_block_infobox_subtitle'),
                'paragraph' => get_sub_field('lb_block_infobox_paragraph'),
                'button' => get_sub_field('lb_block_infobox_btn'),
                'buttonVariants' => get_sub_field('lb_block_infobox_btn_variants')
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




