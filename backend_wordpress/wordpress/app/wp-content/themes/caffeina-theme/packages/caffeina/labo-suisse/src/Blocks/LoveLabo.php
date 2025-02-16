<?php

namespace Caffeina\LaboSuisse\Blocks;

class LoveLabo extends BaseBlock
{
    public function __construct($block, $name, $sectionID)
    {
        parent::__construct($block, $name, $sectionID);

        $items = [];

        for ($i = 1; $i <= 6; $i++) {
            $items[] = [
                'images' => lb_get_images(get_field('lb_block_love_labo_img_' . $i)),
                'text' => get_field('lb_block_love_labo_text_' . $i)
            ];
        }

        $payload = [
            'visibility' => get_field('lb_block_love_labo_visibility') == true,
            'sectionID' => $sectionID ?? null,
            'items' => $items,
            'variants' => [get_field('lb_block_love_labo_variants')]
        ];

        // $this->context['data'] = array_merge($this->context['data'],$infobox);
        $this->setContext($payload);

        $this->addInfobox();
    }
}
