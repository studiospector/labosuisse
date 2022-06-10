<?php

namespace Caffeina\LaboSuisse\Resources\Posts;

use Caffeina\LaboSuisse\Resources\Traits\Arrayable;

class Product
{
    use Arrayable;


    public static function brandCard($brand)
    {
        return [
            'color' => get_field('lb_brand_color', $brand),
            'infobox' => [
                'subtitle' => $brand->name,
                'paragraph' => $brand->description,
                'cta' => [
                    'url' => get_term_link($brand),
                    'title' => __('Scopri il brand', 'labo-suisse-theme'),
                    'variants' => ['quaternary']
                ]
            ],
            'type' => 'type-8',
            'variants' => null
        ];
    }
}
