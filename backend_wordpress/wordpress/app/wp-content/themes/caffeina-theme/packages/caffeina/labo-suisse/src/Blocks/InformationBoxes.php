<?php

namespace Caffeina\LaboSuisse\Blocks;

class InformationBoxes extends BaseBlock
{
    public function __construct($block, $name, $sectionID)
    {
        parent::__construct($block, $name, $sectionID);

        $acf_field = 'block_information_boxes';
        $acf_option = null;
        $items = [];
        
        if ($sectionID) {
            $acf_field = 'product_shipping_info';
            $acf_option = 'option';
        }

        if(have_rows('lb_'. $acf_field .'', $acf_option)) {
            while(have_rows('lb_'. $acf_field .'', $acf_option)) {
                the_row();
                $item = [];

                $item['subtitle'] = get_sub_field('lb_'. $acf_field .'_title', $acf_option);
                $item['paragraph'] = get_sub_field('lb_'. $acf_field .'_text', $acf_option);

                $cta = get_sub_field('lb_'. $acf_field .'_link', $acf_option);
                if (!empty($cta)) {
                    $item['cta'] = array_merge($cta, ['variants' => ['secondary']]);
                }

                $items[] = $item;
            }
        }

        $payload = [
            'sectionID' => $sectionID ?? null,
            'items' => $items,
        ];

        $this->setContext($payload);
    }
}
