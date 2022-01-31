<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class carouselCentered extends BaseBlock {
    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
 // Carousel data
        $items = [];
        if( have_rows('lb_block_carousel_centered') ) {
            while( have_rows('lb_block_carousel_centered') ) : the_row();
                $items[] = [
                    'images' => [
                        'original' => get_sub_field('lb_block_carousel_centered_img'),
                        'large' => get_sub_field('lb_block_carousel_centered_img'),
                        'medium' => get_sub_field('lb_block_carousel_centered_img'),
                        'small' => get_sub_field('lb_block_carousel_centered_img')
                    ],
                    'subtitle' => get_sub_field('lb_block_carousel_centered_subtitle'),
                    'text' => get_sub_field('lb_block_carousel_centered_text')
                ];
            endwhile;
        }
        $payload= [
            'title' => get_field('lb_block_carousel_centered_title'),
            'items' => $items
        ];
        // echo "<pre>";
        // var_dump($payload);
        $this->setContext($payload);  
    }
}



