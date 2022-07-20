<?php

namespace Caffeina\LaboSuisse\Blocks;

class Banner extends BaseBlock
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
            'images' => lb_get_images(get_field('lb_block_banner_img'), $sizes),
            'infoboxBgColorTransparent' => get_field('lb_block_banner_bg_color'),
            'infoboxTextAlignment' => get_field('lb_block_banner_infoboxtextalignment'),
            'variants' => [get_field('lb_block_banner_variants')],
        ];

        $this->setContext($payload);

        $this->addInfobox();
    }
}
