<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class ImageCard extends BaseBlock {
    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
        $payload = [
            'images' => lb_get_images(get_field('lb_block_image_card_image_left')),
            'card' => [
                'images' => lb_get_images(get_field('lb_block_image_card_image_right')),
                'variants' => ['type-7']
            ],
        ];
        $this->addInfobox($payload['card']);
        $this->setContext($payload);   
    }
}
