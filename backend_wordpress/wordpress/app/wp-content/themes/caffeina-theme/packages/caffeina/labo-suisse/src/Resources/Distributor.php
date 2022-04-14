<?php

namespace Caffeina\LaboSuisse\Resources;

use Caffeina\LaboSuisse\Option\Option;

class Distributor
{
    public $title;
    public $content;
    public $phone;
    public $email;
    public $geo_location;
    public $brands;
    public $links;

    public function __construct($post)
    {
        $this->title = $post->post_title;
        $this->content = get_field('lb_distributor_description', $post->ID);
        $this->phone = get_field('lb_distributor_phone', $post->ID);
        $this->email = get_field('lb_distributor_email', $post->ID);
        $this->geo_location = get_field('lb_distributor_address', $post->ID);
        $this->brands = $this->getBrands($post);
        $this->links = [];

        foreach (get_field('lb_distributor_links', $post->ID) as $link) {
            $this->links[] = [
                'label' => $link['lb_distributor_links_label'],
                'links' => $this->parseLinks($link['lb_distributor_links_list']),
            ];
        }

        $this->links[] = $this->getLaboInTheWorldLinks();
    }

    public function toArray()
    {
        $attributes = [];

        foreach (get_object_vars($this) as $key => $value) {
            $attributes[$key] = $value;
        }

        return $attributes;
    }

    private function getBrands($post)
    {
        $brands = wp_get_post_terms($post->ID, 'lb-brand', ['parent' => null]);

        return array_map(function ($item) {
            return [
                'id' => $item->term_id,
                'name' => $item->name
            ];
        }, $brands);
    }

    private function parseLinks($links)
    {
        return array_map(function ($item) {
            return array_merge(
                $item['lb_distributor_links_list_link'],
                [
                    'iconStart' => ['name' => $item['lb_distributor_links_list_icon']],
                    'variants' => ['link'],
                ]
            );
        }, $links);
    }

    private function getLaboInTheWorldLinks()
    {
        $laboInTheWorldLinks = (new Option())->getLaboInTheWorldLink();

        return [
            'label' => __('Scopri i trattamenti distribuiti', 'labo-suisse-theme'),
            'links' => [
                [
                    'title' => __("Crescina in {$this->geo_location['country']}", 'labo-suisse-theme'),
                    'url' => "{$laboInTheWorldLinks['crescina']}#{$this->geo_location['country_short']}",
                    'variants' => ['primary'],
                ],
                [
                    'title' => __("Filerina in {$this->geo_location['country']}", 'labo-suisse-theme'),
                    'url' => "{$laboInTheWorldLinks['filerina']}#{$this->geo_location['country_short']}",
                    'variants' => ['primary'],
                ]
            ]
        ];
    }

}
