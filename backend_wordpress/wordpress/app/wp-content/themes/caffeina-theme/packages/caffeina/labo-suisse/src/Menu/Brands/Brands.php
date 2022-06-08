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

    public function get()
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
                                'title' => __('Scopri di più', 'labo-suisse-theme'),
                                'variants' => ['quaternary']
                            ]
                        ],
                        'type' => 'type-3',
                        'variants' => null
                    ],
                ],
            ]
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

    private function getProductLines($brand)
    {
        $lines =  get_terms(array(
            'taxonomy' => 'lb-brand',
            'hide_empty' => false,
            'parent' => $brand->term_id
        ));

        $items = [
            ['type' => 'link', 'label' => __('Scopri la linea', 'labo-suisse-theme'), 'href' => get_permalink(get_field('lb_brand_page', $brand))],
            ['type' => 'link', 'label' => __('Vedi tutti i prodotti', 'labo-suisse-theme') . ' ' . $brand->name, 'href' => get_term_link($brand)],
        ];

        foreach ($lines as $line) {
            $items[] = [
                'type' => 'link',
                'label' => $line->name,
                'href' => get_term_link($line),
            ];
        }

        return $items;
    }
}
