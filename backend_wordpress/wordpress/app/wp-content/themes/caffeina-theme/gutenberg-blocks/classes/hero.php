<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class Hero extends BaseBlock {
    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
              
        $payload = [
            'images' => [
                'original' => get_field('lb_block_hero_img'),
                'large' => get_field('lb_block_hero_img'),
                'medium' => get_field('lb_block_hero_img'),
                'small' => get_field('lb_block_hero_img')
            ],
            'infoboxPosX' => get_field('lb_block_hero_infoboxposx'),
            'infoboxPosY' => get_field('lb_block_hero_infoboxposy'),
            'container' => get_field('lb_block_hero_container'),
            'variants' => [get_field('lb_block_hero_variants')],
        ];

        // $this->context['data'] = array_merge($this->context['data'],$infobox);
        $this->setContext($payload);
        $this->addInfobox();       
    }
}

