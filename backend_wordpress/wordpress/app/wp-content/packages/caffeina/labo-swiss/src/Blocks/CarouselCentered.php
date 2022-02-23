<?php

namespace Caffeina\LaboSwiss\Blocks;

class CarouselCentered extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);

        $items = [];

        if (have_rows('lb_block_carousel_centered')) {
            while (have_rows('lb_block_carousel_centered')) : the_row();
                $items[] = [
                    'images' => lb_get_images(get_sub_field('lb_block_carousel_centered_img')),
                    'subtitle' => get_sub_field('lb_block_carousel_centered_subtitle'),
                    'text' => get_sub_field('lb_block_carousel_centered_text')
                ];
            endwhile;
        }

        $payload = [
            'title' => get_field('lb_block_carousel_centered_title'),
            'items' => $items
        ];

        $this->setContext($payload);
    }
}
