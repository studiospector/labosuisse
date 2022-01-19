<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class LaunchTwoImages extends BaseBlock {
    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
        $payload = [
            'imagesLeft' => [
                'original' => get_field('lb_block_launch_two_images_image_left'),
                'large' => get_field('lb_block_launch_two_images_image_left'),
                'medium' => get_field('lb_block_launch_two_images_image_left'),
                'small' => get_field('lb_block_launch_two_images_image_left')
            ],
            'imagesRight' => [
                'original' => get_field('lb_block_launch_two_images_image_right'),
                'large' => get_field('lb_block_launch_two_images_image_right'),
                'medium' => get_field('lb_block_launch_two_images_image_right'),
                'small' => get_field('lb_block_launch_two_images_image_right')
            ],
        ];
        $this->setContext($payload);
        $this->addInfobox();
    }
}
