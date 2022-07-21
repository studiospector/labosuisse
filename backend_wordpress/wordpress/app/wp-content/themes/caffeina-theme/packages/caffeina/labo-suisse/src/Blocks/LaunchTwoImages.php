<?php

namespace Caffeina\LaboSuisse\Blocks;

class LaunchTwoImages extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);

        $sizes = [
            'lg' => 'lg',
            'md' => 'lg',
            'sm' => 'lg',
            'xs' => 'lg'
        ];

        $payload = [
            'imagesLeft' => lb_get_images(get_field('lb_block_launch_two_images_image_left'), $sizes),
            'imagesRight' => lb_get_images(get_field('lb_block_launch_two_images_image_right'), $sizes),
        ];

        $this->setContext($payload);

        $this->addInfobox();
    }
}
