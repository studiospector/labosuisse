<?php

namespace Caffeina\LaboSuisse\Blocks;

class NumberListImage extends BaseBlock
{
    public function __construct($block, $name, $sectionID)
    {
        parent::__construct($block, $name, $sectionID);

        $list = [];

        $sizes = [
            'lg' => 'lg',
            'md' => 'lg',
            'sm' => 'lg',
            'xs' => 'lg'
        ];

        if (have_rows('lb_block_numbers_list')) {
            $list = [];
            while (have_rows('lb_block_numbers_list')) : the_row();
                $list[] = [
                    //'number' => get_sub_field('lb_block_numbers_list_number'),
                    'title' => get_sub_field('lb_block_numbers_list_title'),
                    'text' => get_sub_field('lb_block_numbers_list_text'),
                ];

            endwhile;
        }

        $payload = [
            'sectionID' => $sectionID ?? null,
            'images' => lb_get_images(get_field('lb_block_numbers_image'), $sizes),
            'numbersList' => [
                'title' => get_field('lb_block_numbers_title'),
                'list' => $list,
                'variants' => [get_field('lb_block_numbers_variants')]
            ]
        ];

        // $this->addInfobox($payload['leftCard']);
        $this->setContext($payload);
    }
}
