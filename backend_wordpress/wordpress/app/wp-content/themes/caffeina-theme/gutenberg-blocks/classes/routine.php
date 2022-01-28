<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class Routine extends BaseBlock {
    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
        $items = [];
        // Carousel data
        if (have_rows('lb_block_routine_carousel')) {
            $items = [];
            while (have_rows('lb_block_routine_carousel')) : the_row();   
                $cf_post = get_sub_field('lb_block_routine_carousel_product');
                if ( !is_null($cf_post)) {
                 
                    $items[] = [
                        'text' => get_sub_field('lb_block_routine_carousel_text'),
                        'product' => \Timber::get_post($cf_post->ID)
                    ];
                }
            endwhile;
        }

        $payload = [
            'title' =>  get_field('lb_block_routine_title'),
            'items' => $items,
        ];
        $this->setContext($payload);
    }
}

