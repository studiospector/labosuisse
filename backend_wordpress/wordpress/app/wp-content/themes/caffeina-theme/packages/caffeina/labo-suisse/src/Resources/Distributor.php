<?php

namespace Caffeina\LaboSuisse\Resources;

use Caffeina\LaboSuisse\Option\Option;
use Caffeina\LaboSuisse\Resources\Traits\Arrayable;

class Distributor
{
    use Arrayable;

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
            $links = $this->parseLinks($link['lb_distributor_links_list']);
            if(!empty($links)) {
                $this->links[] = [
                    'label' => $link['lb_distributor_links_label'],
                    'links' => $links,
                ];
            }
        }

        $this->links[] = $this->getLaboInTheWorldLinks();

        $this->read_more = [
            'title' => __('Leggi di più', 'labo-suisse-theme'),
            'type' => 'button',
            'class' => 'lb-template-distributor__map__infowindow__more-text js-infowindow-more-text',
            'attributes' => ['data-more-text="'. __('Leggi di più', 'labo-suisse-theme') .'"', 'data-less-text="'. __('Vedi meno dettagli', 'labo-suisse-theme') .'"'],
            'variants' => ['link', 'small']
        ];
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
        if (empty($links)) {
            return [];
        }

        return array_filter(
            array_map(function ($item) {
                if ($item['lb_distributor_links_list_link'] != "") {
                    return array_merge(
                        $item['lb_distributor_links_list_link'],
                        [
                            'iconStart' => ['name' => $item['lb_distributor_links_list_icon'] == 'default' ? 'link' : $item['lb_distributor_links_list_icon']],
                            'variants' => ['link'],
                        ]
                    );
                }
            }, $links)
        );
    }

    private function getLaboInTheWorldLinks()
    {
        if(empty($this->geo_location)) {
            return [];
        }

        $laboInTheWorldLinks = (new Option())->getLaboInTheWorldLink();
        $brands = array_column($this->brands,'name');
        $links = [];

        if(in_array('Fillerina', $brands)) {
            $links[] = [
                'title' => __("Filerina in {$this->geo_location['country']}", 'labo-suisse-theme'),
                'url' => "{$laboInTheWorldLinks['filerina']}#{$this->geo_location['country_short']}",
                'target' => '_blank',
                'variants' => ['primary'],
            ];
        }

        if(in_array('Crescina', $brands)) {
            $links[] = [
                'title' => __("Crescina in {$this->geo_location['country']}", 'labo-suisse-theme'),
                'url' => "{$laboInTheWorldLinks['crescina']}#{$this->geo_location['country_short']}",
                'target' => '_blank',
                'variants' => ['primary'],
            ];
        }

        return [
            'label' => __('Scopri i trattamenti distribuiti', 'labo-suisse-theme'),
            'links' => $links
        ];
    }
}
