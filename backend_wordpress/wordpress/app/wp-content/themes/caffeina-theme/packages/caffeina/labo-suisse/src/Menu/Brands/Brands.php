<?php

namespace Caffeina\LaboSuisse\Menu\Brands;

use Caffeina\LaboSuisse\Menu\Traits\HasGetTerms;
use Caffeina\LaboSuisse\Option\Option;

class Brands
{
    use HasGetTerms;

    private $brands = [];

    private $option = null;

    public function __construct()
    {
        $this->brands = $this->getTerms('lb-brand', [
            'parent' => null
        ]);

        $this->option = new Option();
    }

    public function get($device = 'desktop')
    {
        return ($device == 'desktop')
            ? $this->desktop()
            : $this->mobile();
    }

    private function desktop()
    {
        $menu = [
            'type' => 'submenu',
            'label' => __('Tutti i Brand', 'labo-suisse-theme'),
            'children' => [
                [
                    'type' => 'submenu',
                    'label' => __('Per Brand', 'labo-suisse-theme'),
                    'children' => [
                        ['type' => 'link', 'label' => 'Tutti i brand', 'href' => $this->option->getArchiveBrandLink()]
                    ]
                ],
                [
                    'type' => 'submenu-second',
                    'children' => []
                ]
            ],
            'fixed' => [ (new Option())->getMenuFixedCard('brands')]
        ];

        foreach ($this->brands as $i => $brand) {
            $menu['children'][0]['children'][] = [
                'type' => 'submenu-link',
                'label' => $brand->name,
                'trigger' => md5($brand->slug),
            ];

            $menu['children'][1]['children'][$i] = [
                'type' => 'submenu',
                'label' => __('Per linea di prodotto', 'labo-suisse-theme'),
                'trigger' => md5($brand->slug),
                'children' => $this->getProductLines($brand)
            ];
        }

        return [$menu];
    }

    private function mobile()
    {
        $items  = [
            'type' => 'submenu',
            'label' => __('Tutti i Brand', 'labo-suisse-theme'),
            'children' => [],
        ];

        foreach ($this->brands as $i => $brand) {
            $items['children'][] = [
                'type' => 'submenu',
                'label' => $brand->name,
                'children' => $this->getProductLines($brand, 'mobile')
            ];
        }

        return [$items];
    }

    private function getProductLines($brand, $device = 'desktop')
    {
        $lines =  get_terms(array(
            'taxonomy' => 'lb-brand',
            'hide_empty' => false,
            'parent' => $brand->term_id
        ));

        $items = [
            ['type' => 'link', 'label' => __('Scopri la linea', 'labo-suisse-theme'), 'href' => get_permalink(get_field('lb_brand_page', $brand))]
        ];

        foreach ($lines as $line) {
            $items[] = [
                'type' => 'link',
                'label' => $line->name,
                'href' => get_term_link($line),
            ];
        }

        $type = ($device === 'desktop')
            ? 'link-spaced'
            : 'link';

        $items[] = ['type' => $type, 'label' => __('Vedi tutti i prodotti', 'labo-suisse-theme') . ' ' . $brand->name, 'href' => get_term_link($brand)];
        return $items;
    }
}
