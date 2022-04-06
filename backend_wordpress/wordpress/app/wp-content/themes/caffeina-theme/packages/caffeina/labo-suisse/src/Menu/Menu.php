<?php

namespace Caffeina\LaboSuisse\Menu;

use Caffeina\LaboSuisse\Menu\Brands\Brands;
use Caffeina\LaboSuisse\Menu\Product\Macro;
use Caffeina\LaboSuisse\Menu\DiscoverLabo\DiscoverLabo;
use Caffeina\LaboSuisse\Option\Option;

class Menu
{
    public static function desktop()
    {
        $desktop = array_merge(
            (new Macro())->get(),
            [['type' => 'separator']],
            (new Brands())->get(),
            (new DiscoverLabo())->get()
        );

//         if (is_woocommerce()) {
//             $desktop = array_merge($desktop, (new Option())->getLShopLinks());
//         }

        return $desktop;
    }

    public static function mobile()
    {
        $lang_selector = do_shortcode('[wpml_language_selector_widget]');

        return [
            'children' => array_merge(
                (new Macro())->get('mobile'),
                (new DiscoverLabo())->get('mobile')
            ),
            'fixed' => [
                [
                    'type' => 'card',
                    'data' => [
                        'images' => [
                            'original' => get_stylesheet_directory_uri() . '/assets/images/card-img-5.jpg',
                            'lg' => get_stylesheet_directory_uri() . '/assets/images/card-img-5.jpg',
                            'md' => get_stylesheet_directory_uri() . '/assets/images/card-img-5.jpg',
                            'sm' => get_stylesheet_directory_uri() . '/assets/images/card-img-5.jpg',
                            'xs' => get_stylesheet_directory_uri() . '/assets/images/card-img-5.jpg',
                        ],
                        'infobox' => [
                            'subtitle' => 'AFTER MASK',
                            'paragraph' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.',
                            'cta' => [
                                'url' => '#',
                                'title' => 'Scopri la linea',
                                'variants' => ['quaternary']
                            ]
                        ],
                        'variants' => ['type-1']
                    ],
                ],
                [
                    'type' => 'small-link',
                    'label' => __('Profilo', 'labo-suisse-theme'),
                    'icon' => 'user',
                    'href' => get_permalink(get_option('woocommerce_myaccount_page_id')),
                ],
                [
                    'type' => 'small-link',
                    'label' => __('Hai bisogno di aiuto?', 'labo-suisse-theme'),
                    'icon' => 'comments',
                ],
                [
                    'type' => 'lang-selector',
                    'language_selector' => (!empty($lang_selector)) ? true : false,
                ],
            ]
        ];
    }
}
