<?php

namespace Caffeina\LaboSwiss\Blocks;

class LaunchTwoImages extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);
        $payload = [
            'imagesLeft' => lb_get_images(get_field('lb_block_launch_two_images_image_left')),
            'imagesRight' => lb_get_images(get_field('lb_block_launch_two_images_image_right')),
        ];

        $this->setContext($payload);

        $this->addInfobox();
    }
}
