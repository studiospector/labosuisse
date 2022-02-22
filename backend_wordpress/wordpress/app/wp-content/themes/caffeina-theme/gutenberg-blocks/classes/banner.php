<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class Banner extends BaseBlock {
    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
        $payload = [
            'images' => lb_get_images(get_field('lb_block_banner_img')),
            'infoboxBgColorTransparent' => get_field('lb_block_banner_bg_color'),
            'infoboxTextAlignment' => get_field('lb_block_banner_infoboxtextalignment'),
            'variants' => [get_field('lb_block_banner_variants')],
        ];
        
        $this->setContext($payload);
        
        $this->addInfobox();
        
    }
}


