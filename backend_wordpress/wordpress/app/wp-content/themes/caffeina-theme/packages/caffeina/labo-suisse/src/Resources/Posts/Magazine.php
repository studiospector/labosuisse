<?php

namespace Caffeina\LaboSuisse\Resources\Posts;

use Caffeina\LaboSuisse\Resources\Traits\Arrayable;
use Caffeina\LaboSuisse\Resources\Traits\HasImages;
use Carbon\Carbon;

class Magazine
{
    use Arrayable, HasImages;

    private $post;
    private $typlogy;

    public $images;
    public $date;
    public $variants;
    public $infobox;
    public $col_classes = ['col-12', 'col-md-3'];

    public function __construct($post)
    {
        $this->post = $post;

        $this->typlogy = $this->getTypology();
        $this->images = $this->getImages();
        $this->date = $this->getDate();
        $this->variants = $this->getVariants();
        $this->infobox = $this->getInfobox();
    }

    private function getTypology()
    {
        return get_field('lb_post_typology', $this->post->ID);
    }

    private function getDate()
    {
        return Carbon::createFromDate($this->post->post_date)->format('d/m/Y');
    }

    private function getVariants()
    {
        $variant = 'type-2';

        if ($this->typlogy === 'press') {
            $variant = 'type-6';
        }

        return [$variant];
    }

    private function getInfobox()
    {
        $image = ($this->typlogy === 'press')
            ?  get_field('lb_post_press_logo', $this->post->ID)
            : null;

        $cta_title = ($this->typlogy === 'press')
            ? __("Visualizza", "labo-suisse-theme")
            : __("Leggi l'articolo", "labo-suisse-theme");

        return [
            'image' => $image,
            'subtitle' => $this->post->post_title,
            'paragraph' => $this->post->post_excerpt,
            'cta' => [
                'title' => $cta_title,
                'url' => get_permalink($this->post->ID),
                'iconEnd' => ['name' => 'arrow-right'],
                'variants' => ['quaternary']
            ]
        ];
    }
}
