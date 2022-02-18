<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class NumberListImage extends BaseBlock {
    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
        $list = [];
        if( have_rows('lb_block_numbers_list') ) {
            $list = [];
            while( have_rows('lb_block_numbers_list') ) : the_row();
                $list[] = [
                    //'number' => get_sub_field('lb_block_numbers_list_number'),
                    'title' => get_sub_field('lb_block_numbers_list_title'),
                    'text' => get_sub_field('lb_block_numbers_list_text'),
                ];
               
            endwhile;
        }
        
        $payload= [
            'images' => lb_get_images(get_field('lb_block_numbers_image')),
            'numbersList' => [
                'title' => get_field('lb_block_numbers_title'),
                'list' => $list,
                'variants' => [get_field('lb_block_numbers_variants')]
            ]
        ];
        
        // $this->addInfobox($payload['leftCard']);
        $this->setContext($payload);
           
    }
}






