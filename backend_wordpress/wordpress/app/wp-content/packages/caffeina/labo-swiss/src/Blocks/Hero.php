<?php

namespace Caffeina\LaboSwiss\Blocks;

class Hero extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);

        $payload = [
            'images' => lb_get_images(get_field('lb_block_hero_img')),
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
