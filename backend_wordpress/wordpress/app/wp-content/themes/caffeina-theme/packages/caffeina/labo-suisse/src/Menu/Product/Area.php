<?php

namespace Caffeina\LaboSuisse\Menu\Product;

use Caffeina\LaboSuisse\Menu\Traits\HasGetTerms;
use Caffeina\LaboSuisse\Option\Option;

class Area
{
    use HasGetTerms;

    private $areas = [];
    private $parent;
    private $device;

    public function __construct($parent, $device)
    {
        $this->parent = $parent;
        $this->device = $device;

        $this->areas = $this->getTerms('product_cat', [
            'parent' => $parent->term_id
        ]);
    }

    public function get()
    {
        return ($this->device == 'desktop')
            ? $this->desktop()
            : $this->mobile();
    }

    private function desktop()
    {
        $items = [
            'type' => 'submenu',
            'label' => $this->parent->name,
            'children' => [
                [
                    'type' => 'submenu',
                    'label' => 'Per Zona',
                    'children' => []
                ],
                [
                    'type' => 'submenu-second',
                    'children' => []
                ]
            ],
            'fixed' => [
                [
                    'type' => 'card',
                    'data' => [
                        'images' => [
                            'original' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                            'lg' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                            'md' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                            'sm' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                            'xs' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                        ],
                        'infobox' => [
                            'subtitle' => 'Magnetic Eyes',
                            'paragraph' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                            'cta' => [
                                'url' => '#',
                                'title' => 'Scopri di più',
                                'iconEnd' => ['name' => 'arrow-right'],
                                'variants' => ['quaternary']
                            ]
                        ],
                        'type' => 'type-3',
                        'variants' => null
                    ],
                ],
            ]
        ];

        foreach ($this->areas as $i => $area) {
            $items['children'][0]['children'][] = [
                'type' => 'submenu-link',
                'label' => $area->name,
                'trigger' => md5($area->slug),
            ];

            $items['children'][1]['children'][$i] = [
                'type' => 'submenu',
                'label' => 'Per Esigenza',
                'trigger' => md5($area->slug),
                'children' => (new Need($area))->get()
            ];
        }

        $items['children'][0]['children'][] = ['type' => 'link', 'label' => 'Tutte le zone ' . strtolower($this->parent->name), 'href' => get_term_link($this->parent)];
        $items['children'][0]['children'][] = ['type' => 'link-spaced', 'label' => 'Tutti i prodotti ' . strtolower($this->parent->name), 'href' => (new Option())->getProductGalleryLink($this->parent->slug)];

        return $this->fixDesktopMenu($items);
    }

    private function mobile()
    {
        $items = [
            'type' => 'submenu',
            'label' => $this->parent->name,
            'subLabel' => 'Per zona',
            'children' => []

        ];

        foreach ($this->areas as $i => $area) {
            $items['children'][] = [
                'type' => 'submenu',
                'label' => $area->name,
                'subLabel' => 'Per esigenza',
                'children' => (new Need($area))->get()
            ];
        }

        $items['children'][] = ['type' => 'link', 'label' => 'Tutte le zone ' . strtolower($this->parent->name), 'href' => get_term_link($this->parent)];
        $items['children'][] = ['type' => 'link', 'label' => 'Tutti i prodotti ' . strtolower($this->parent->name), 'href' => (new Option())->getProductGalleryLink($this->parent->slug)];

        return $this->fixMobileMenu($items);
    }

    private function fixDesktopMenu($items)
    {
        $areaName = $items['label'];
        $totalNeed = count($items['children'][0]['children']);
        $needName = $items['children'][0]['children'][0]['label'];

        if ($totalNeed === 3 and ($areaName === $needName)) {
            unset($items['children'][0]);
            $items['children'][1] = $items['children'][1]['children'][0];
            array_unshift($items['children']);

            $items['children'][0]['children'][] = ['type' => 'link-spaced', 'label' => 'Tutti i prodotti ' . strtolower($this->parent->name), 'href' => (new Option())->getProductGalleryLink($this->parent->slug)];
        }

        return $items;
    }

    private function fixMobileMenu($items)
    {
        $areaName = $items['label'];
        $totalNeed = count($items['children']);
        $needName = $items['children'][0]['label'];

        if($totalNeed === 2 and ($areaName === $needName)) {
            $items = $items['children'][0];
        }

        return $items;
    }
}
