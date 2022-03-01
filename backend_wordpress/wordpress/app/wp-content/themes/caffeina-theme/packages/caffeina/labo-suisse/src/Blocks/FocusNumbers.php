<?php

namespace Caffeina\LaboSuisse\Blocks;

class FocusNumbers extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);
        $items = [];

        if (have_rows('lb_block_focus_numbers_focuses')) {
            while (have_rows('lb_block_focus_numbers_focuses')) : the_row();
                $items[] = [
                    'number' => get_sub_field('lb_block_focus_numbers_focuses_number'),
                    'title' => get_sub_field('lb_block_focus_numbers_focuses_title'),
                    'text' => get_sub_field('lb_block_focus_numbers_focuses_text')
                ];
            endwhile;
        }

        $payload = [
            'shield' => get_field('lb_block_focus_numbers_shield'),
            'image' => get_field('lb_block_focus_numbers_img'),
            'subtitle' => get_field('lb_block_focus_numbers_subtitle'),
            'text' => get_field('lb_block_focus_numbers_text'),
            'focuses' => $items
        ];

        $this->setContext($payload);
    }
}
