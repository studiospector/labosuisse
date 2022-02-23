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
                if ( !empty($cf_post) ) {
                 
                    $items[] = [
                        'text' => get_sub_field('lb_block_routine_carousel_text'),
                        'product' => \Timber::get_post($cf_post->ID)
                    ];
                }
            endwhile;
        }

        $payload = [
            'title' =>  get_field('lb_block_routine_title'),
            'cta' => (get_field('lb_block_routine_btn')) ? array_merge( (array)get_field('lb_block_routine_btn'), ['variants' => [get_field('lb_block_routine_btn_variants')]]) : null,
            'items' => $items,
            'variants' => [get_field('lb_block_routine_variants')],
        ];
        $this->setContext($payload);
    }
}
