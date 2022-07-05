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
                    'label' => __('Per Zona', 'labo-suisse-theme'),
                    'children' => []
                ],
                [
                    'type' => 'submenu-second',
                    'children' => []
                ]
            ],
            'fixed' => [ (new Option())->getMenuFixedCardByTaxTerm($this->parent)]
        ];

        foreach ($this->areas as $i => $area) {
            $items['children'][0]['children'][] = [
                'type' => 'submenu-link',
                'label' => $area->name,
                'trigger' => md5($area->slug),
            ];

            $items['children'][1]['children'][$i] = [
                'type' => 'submenu',
                'label' => __('Per Esigenza', 'labo-suisse-theme'),
                'trigger' => md5($area->slug),
                'children' => (new Need($area))->get()
            ];
        }

//        $items['children'][0]['children'][] = [
//            'type' => 'link',
//            'label' => sprintf(__("Tutte le zone %s", 'labo-suisse-theme'), strtolower($this->parent->name)),
//            'href' => get_term_link($this->parent)
//        ];
        $items['children'][0]['children'][] = [
            'type' => 'link-spaced',
            'label' => sprintf(__("Tutti i prodotti %s", 'labo-suisse-theme'), strtolower($this->parent->name)),
            'href' => get_term_link($this->parent)
        ];

        return $this->fixDesktopMenu($items);
    }

    private function mobile()
    {
        $items = [
            'type' => 'submenu',
            'label' => $this->parent->name,
            'subLabel' => __('Per Zona', 'labo-suisse-theme'),
            'children' => []

        ];

        foreach ($this->areas as $i => $area) {
            $items['children'][] = [
                'type' => 'submenu',
                'label' => $area->name,
                'subLabel' => __('Per Esigenza', 'labo-suisse-theme'),
                'children' => (new Need($area))->get()
            ];
        }

        $items['children'][] = [
            'type' => 'link',
            'label' => sprintf(__("Tutte le zone %s", 'labo-suisse-theme'), strtolower($this->parent->name)),
            'href' => get_term_link($this->parent)
        ];
        $items['children'][] = [
            'type' => 'link',
            'label' => sprintf(__("Tutti i prodotti %s", 'labo-suisse-theme'), strtolower($this->parent->name)),
            'href' => (new Option())->getProductGalleryLink($this->parent->slug)
        ];

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

            $items['children'][0]['children'][] = [
                'type' => 'link-spaced',
                'label' => sprintf(__("Tutti i prodotti %s", 'labo-suisse-theme'), strtolower($this->parent->name)),
                'href' => (new Option())->getProductGalleryLink($this->parent->slug)
            ];
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
