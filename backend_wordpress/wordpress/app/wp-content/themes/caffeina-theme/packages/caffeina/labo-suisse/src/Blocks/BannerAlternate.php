<?php

namespace Caffeina\LaboSuisse\Blocks;

class BannerAlternate extends BaseBlock
{
    public function __construct($block, $name, $sectionID)
    {
        parent::__construct($block, $name, $sectionID);

        $sizes = [
            'lg' => 'lg',
            'md' => 'lg',
            'sm' => 'lg',
            'xs' => 'lg'
        ];

        $disable_animation = get_field('lb_block_banner_alternate_disable_animation');

        $payload = [
            'sectionID' => $sectionID ?? null,
            'images' => lb_get_images(get_field('lb_block_banner_alternate_img'), $sizes),
            'imageBig' => get_field('lb_block_banner_alternate_img_big'),
            'animations' => $disable_animation ? false : true,
            'variants' => [get_field('lb_block_banner_alternate_variants_lr'), get_field('lb_block_banner_alternate_variants_hcb')],
        ];

        $this->setContext($payload);

        $this->addInfobox();
    }
}
