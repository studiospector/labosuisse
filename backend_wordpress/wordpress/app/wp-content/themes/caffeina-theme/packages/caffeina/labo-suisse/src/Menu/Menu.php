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

        // if (is_woocommerce()) {
            $desktop = array_merge($desktop, (new Option())->getLShopLinks());
        // }

        return $desktop;
    }

    public static function mobile()
    {
        $lang_selector = do_shortcode('[wpml_language_selector_widget]');

        return [
            'children' => array_merge(
                (new Macro())->get('mobile'),
                (new Brands())->get('mobile'),
                (new DiscoverLabo())->get('mobile')
            ),
            'fixed' => [
                [
                    'type' => 'small-link',
                    'label' => __('Carrello', 'labo-suisse-theme'),
                    'icon' => ['name' => 'cart', 'counter' => WC()->cart->get_cart_contents_count()],
                    'href' => wc_get_cart_url(),
                ],
                [
                    'type' => 'small-link',
                    'label' => __('Profilo', 'labo-suisse-theme'),
                    'icon' => 'user',
                    'href' => '#',
                    // 'href' => get_permalink(get_option('woocommerce_myaccount_page_id')),
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
