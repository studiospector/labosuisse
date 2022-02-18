<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class cardsGrid extends BaseBlock {

    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
        $items = [];
        if( have_rows('lb_block_cards_grid_carousel') ) {
            $items = [];
            while( have_rows('lb_block_cards_grid_carousel') ) : the_row();
                $cta = [];
                if (get_sub_field('lb_block_cards_grid_carousel_infobox_btn') != "") {
                    $cta = array_merge (  (array)get_sub_field('lb_block_cards_grid_carousel_infobox_btn') ,['variants' => [get_sub_field('lb_block_cards_grid_carousel_infobox_btn_variants')]]);  
                }
                $items[] = [
                    'images' => lb_get_images(get_sub_field('lb_block_cards_grid_carousel_img')),
                    'noContainer' => true,
                    'infobox' => [
                        'tagline' => get_sub_field('lb_block_cards_grid_carousel_infobox_tagline'),
                        'title' => get_sub_field('lb_block_cards_grid_carousel_infobox_title'),
                        'subtitle' => get_sub_field('lb_block_cards_grid_carousel_infobox_subtitle'),
                        'paragraph' => get_sub_field('lb_block_cards_grid_carousel_infobox_paragraph'),
                        'cta' => $cta
                    ],
                    'variants' => [get_field('lb_block_cards_grid_cards_variants')],
                ];
                
            endwhile;

        
        }
        $ctaCards =[];
        if (get_field('lb_block_cards_grid_btn') != "") {
            $ctaCards = array_merge (  (array)get_field('lb_block_cards_grid_btn') ,['variants' => [get_field('lb_block_cards_grid_btn_variants')]]);
            
        }
        $payload = [
            'tagline' =>  get_field('lb_block_cards_grid_tagline'),
            'title' =>  get_field('lb_block_cards_grid_title'),
            'paragraph' =>  get_field('lb_block_cards_grid_paragraph'),
            'cta' => $ctaCards,
            'items' => $items,
        ];
        $this->setContext($payload);
    }

}