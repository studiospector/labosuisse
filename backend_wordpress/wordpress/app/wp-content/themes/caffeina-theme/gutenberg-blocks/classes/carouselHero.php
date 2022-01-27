<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class carouselHero extends BaseBlock {
    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
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
                    'infoboxPosX' => get_sub_field('lb_block_carousel_hero_infoboxposx'),
                    'infoboxPosY' => get_sub_field('lb_block_carousel_hero_infoboxposy'),
                    'container' => get_sub_field('lb_block_carousel_hero_container'),
                    'variants' => get_sub_field('lb_block_carousel_hero_variants'),
                    'infobox' => [
                        'tagline' => get_sub_field('lb_block_carousel_hero_infobox_tagline'),
                        'title' => get_sub_field('lb_block_carousel_hero_infobox_title'),
                        'subtitle' => get_sub_field('lb_block_carousel_hero_infobox_subtitle'),
                        'paragraph' => get_sub_field('lb_block_carousel_hero_infobox_paragraph'),
                        'cta' => array_merge(
                            (array)get_sub_field('lb_block_carousel_hero_infobox_btn'),
                            [
                                'variants' => [
                                    get_sub_field('lb_block_carousel_hero_infobox_btn_variants')
                                ]
                            ]
                        )
                    ]
                ];
            endwhile;
        }
        $payload= [
            'slides' => $slides
        ];
        // $this->context['data'] = array_merge($this->context['data'],$infobox);
        $this->setContext($payload);  
    }
}



