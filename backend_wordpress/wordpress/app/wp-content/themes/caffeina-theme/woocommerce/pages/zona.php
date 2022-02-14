<?php
require_once(__DIR__ . '/basePage.php');
require_once(__DIR__ . '/esigenza.php');

use pages\basePage;

class zona extends basePage
{

    public function __construct($name, $term)
    {
        parent::__construct('zona', $term);
        $payload =   get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => $term->term_id
        ));

        $this->setContext($payload);
        //$this->macro->render();
    }

    public static function getAll($parent, $withCard = true)
    {
        $areas = self::getProductCategory($parent);


        $items = [
            'type' => 'submenu',
            'label' => $parent->name,
            'children' => [
                [
                    'type' => 'submenu',
                    'label' => 'Per Zona',
                    'children' => [
                        ['type' => 'link', 'label' => 'Tutte le zone ' . strtolower($parent->name), 'href' =>  get_term_link($parent)]
                    ]
                ],
                [
                    'type' => 'submenu-second',
                    'children' => []
                ]
            ]
        ];

        if ($withCard) {
            $items['fixed'] = [
                [
                    'type' => 'card',
                    'data' => [
                        'images' => [
                            'original' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                            'large' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                            'medium' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                            'small' => get_template_directory_uri() . '/assets/images/card-img-5.jpg'
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
                        'variants' => ['type-3']
                    ],
                ],
            ];
        }

        foreach ($areas as $i => $zone) {
            $items['children'][0]['children'][] = [
                'type' => 'submenu-link',
                'label' => $zone->name,
                'trigger' => md5($zone->slug),
            ];

            $items['children'][1]['children'][$i] = [
                'type' => 'submenu',
                'label' => 'Per Esigenza',
                'trigger' => md5($zone->slug),
                'children' => esigenza::getAll($zone)

            ];
        }

        return $items;
    }
}
