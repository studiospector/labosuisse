<?php

namespace Caffeina\LaboSuisse\Blocks;

class ImageCard extends BaseBlock
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
            'images' => lb_get_images(get_field('lb_block_image_card_image_left'), $sizes),
            'card' => [
                'images' => lb_get_images(get_field('lb_block_image_card_image_right')),
                'type' => 'type-7',
                'variants' => null
            ],
        ];

        $this->addInfobox($payload['card']);

        $this->setContext($payload);
    }
}
