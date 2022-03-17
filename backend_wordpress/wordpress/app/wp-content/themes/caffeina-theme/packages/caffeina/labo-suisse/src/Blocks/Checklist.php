<?php

namespace Caffeina\LaboSuisse\Blocks;

class Checklist extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);

        $items = [];

        if(have_rows('lb_block_checklist_items')) {

            while(have_rows('lb_block_checklist_items')) {
                the_row();

                $items[] = get_sub_field('lb_block_checklist_items');
            }
        }

        $paylod = [
            'items' => $items
        ];

        $this->setContext($paylod);
    }
}
