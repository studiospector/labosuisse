<?php

namespace Caffeina\LaboSwiss\Blocks;

class NumberList extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);

        $list = [];

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
            'title' => get_field('lb_block_numbers_title'),
            'list' => $list,
            'variants' => [get_field('lb_block_numbers_variants')]
        ];

        $this->setContext($payload);
    }
}
