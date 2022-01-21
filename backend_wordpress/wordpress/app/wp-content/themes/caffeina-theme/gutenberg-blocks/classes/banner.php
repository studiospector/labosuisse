<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class Banner extends BaseBlock {
    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
        $payload = [
            'images' => [
                'original' => get_field('lb_block_banner_img'),
                'large' => get_field('lb_block_banner_img'),
                'medium' => get_field('lb_block_banner_img'),
                'small' => get_field('lb_block_banner_img')
            ],
            'infoboxBgColorTransparent' => get_field('lb_block_banner_bg_color'),
            'infoboxTextAlignment' => get_field('lb_block_banner_infoboxtextalignment'),
            'variants' => get_field('lb_block_banner_variants'),
        
        ];
        
        $this->setContext($payload);
        
        $this->addInfobox();
        
    }
}


