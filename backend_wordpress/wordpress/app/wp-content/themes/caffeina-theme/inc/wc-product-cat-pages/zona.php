<?php
require_once(__DIR__ . '/basePage.php');
require_once(__DIR__ . '/esigenza.php');

use pages\basePage;

class zona extends basePage
{

    public function __construct($name, $term)
    {
        parent::__construct('zona', $term);

        $sub_terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => $term->term_id,
        ]);

        $payload['terms'] = [];
        foreach ($sub_terms as $item) {
            // Term Thumb
            $thumbnail_id = get_term_meta($item->term_id, 'thumbnail_id', true);
            $image = wp_get_attachment_url($thumbnail_id);

            $payload['terms'][] = [
                'images' => [
                    'original' => $image,
                    'large' => $image,
                    'medium' => $image,
                    'small' => $image
                ],
                'infobox' => [
                    'subtitle' => $item->name,
                    'paragraph' => $item->description,
                    'cta' => [
                        'url' => get_term_link($item),
                        'title' => 'Vai a ' . $item->name,
                        'variants' => ['quaternary']
                    ]
                ],
                'variants' => ['type-3']
            ];
        }

        $this->setContext($payload);
        //$this->macro->render();
    }

    public static function getDesktopItems($parent)
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
            ],
            'fixed' => [
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
            ]
        ];

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

    public static function getMobileItems($parent)
    {
        $areas = self::getProductCategory($parent);

        $items = [
            'type' => 'submenu',
            'label' => $parent->name,
            'subLabel' => 'Per zona',
            'children' => [
                ['type' => 'link', 'label' => 'Tutte le zone ' . strtolower($parent->name), 'href' =>  get_term_link($parent)]
            ]

        ];

        foreach ($areas as $i => $zone) {
            $items['children'][] = [
                'type' => 'submenu',
                'label' => $zone->name,
                'subLabel' => 'Per esigenza',
                'children' => esigenza::getAll($zone)
            ];
        }

        return $items;
    }
}
