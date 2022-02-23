<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class BannerAlternate extends BaseBlock {
    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
        $payload = [
            'images' => lb_get_images(get_field('lb_block_banner_alternate_img')),
            'imageBig' => get_field('lb_block_banner_alternate_img_big'),
            'variants' => [get_field('lb_block_banner_alternate_variants_lr'),get_field('lb_block_banner_alternate_variants_hcb')],
        ];
        
        $this->setContext($payload);
        
        $this->addInfobox();
        
    }
}
