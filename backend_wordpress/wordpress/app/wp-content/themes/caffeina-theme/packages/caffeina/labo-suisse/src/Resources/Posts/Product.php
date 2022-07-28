<?php

namespace Caffeina\LaboSuisse\Resources\Posts;

use Caffeina\LaboSuisse\Resources\Traits\Arrayable;

class Product
{
    use Arrayable;


    public static function brandCard($brand)
    {
        $brand_page = get_field('lb_brand_page', $brand);

        return [
            'color' => get_field('lb_brand_color', $brand),
            'infobox' => [
                'subtitle' => $brand->name,
                'paragraph' => $brand->description,
                'cta' => [
                    'url' => get_permalink($brand_page),
                    'title' => __('Scopri il brand', 'labo-suisse-theme'),
                    'variants' => ['quaternary']
                ]
            ],
            'type' => 'type-8',
            'variants' => null
        ];
    }
}
