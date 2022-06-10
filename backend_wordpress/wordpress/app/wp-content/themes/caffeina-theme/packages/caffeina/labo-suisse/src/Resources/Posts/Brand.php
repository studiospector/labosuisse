<?php

namespace Caffeina\LaboSuisse\Resources\Posts;

use Caffeina\LaboSuisse\Resources\Traits\Arrayable;

class Brand
{
    use Arrayable;

    private $term;

    public $images;
    public $infobox;
    public $type = 'type-10';
    public $col_classes = ['col-12', 'col-md-4'];

    public function __construct($term)
    {
        $this->term = $term;

        $this->images = lb_get_images(get_field('lb_brand_image', $this->term));
        $this->infobox = $this->getInfobox();
    }

    private function getInfobox()
    {
        $brand_page = get_field('lb_brand_page', $this->term);
        $cta = null;

        if (!empty($brand_page)) {
            $cta = [
                'url' => get_permalink($brand_page),
                'title' => __('Vai al brand', 'labo-suisse-theme'),
                'variants' => ['quaternary']
            ];
        }

        return [
            'subtitle' => $this->term->name,
            'paragraph' => $this->term->description,
            'cta' => $cta
        ];
    }
}
