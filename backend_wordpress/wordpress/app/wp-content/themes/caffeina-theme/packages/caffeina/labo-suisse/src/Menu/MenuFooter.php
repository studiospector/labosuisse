<?php

namespace Caffeina\LaboSuisse\Menu;

use Caffeina\LaboSuisse\Api\Multicountry\Geolocation;
use Caffeina\LaboSuisse\Menu\DiscoverLabo\DiscoverLaboFooter;
use Caffeina\LaboSuisse\Menu\Support\Support;
use Caffeina\LaboSuisse\Menu\Impressum\Impressum;
use Caffeina\LaboSuisse\Option\Option;

class MenuFooter
{
    public static function get()
    {
        $prefooter = [];

        if (!is_home()) {
            $options = (new Option())->getPreFooterOptions();

            $prefooter = [
                'block_1' => self::leftBlock($options['left']),
                'block_2' => self::centerBlock($options['center']),
                'block_3' => self::rightBlock($options['right'])
            ];
        }

        return [
            'prefooter' => $prefooter,
            'pictograms' => self::pictograms(),
            'footer' => [
                'discover' => (new DiscoverLaboFooter())->get(),
                'support' => (new Support())->get(),
                'search' => self::search(),
                'newsletter' => self::newsletter(),
                'social' => self::social(),
                'impressum' => self::impressum(),
            ]
        ];
    }

    private static function search()
    {
        $payload = [];
        $lang = lb_get_current_lang();
        $search = (new Option())
            ->getFooterSearchOptions();

        if ($lang != 'it') {
            $payload = [
                'items' => [
                    array_merge($search['link'], ['variants' => ['link', 'thin', 'small']])
                ]
            ];
        } else {
            $payload = [
                'form' => [
                    'action' => get_post_type_archive_link('lb-store'),
                    'input' => [
                        'type' => 'search',
                        'id' => "lb-search-val-footer",
                        'name' => "lb-search-val",
                        'label' => $search['label'] ?? null,
                        'disabled' => false,
                        'required' => false,
                        'buttonTypeNext' => 'submit',
                        'variants' => ['secondary'],
                    ],
                ],
            ];
        }

        return array_merge([
            'title' => $search['title'] ?? null,
        ], $payload);
    }

    private static function newsletter()
    {
        $newsletter = (new Option())
            ->getFooterNewsletterOptions();

        return [
            'title' => $newsletter['title'] ?? null,
            'text' => $newsletter['text'] ?? null,
            'cta' => $newsletter['cta'] ?? null
        ];
    }

    private static function social()
    {
        return (new Option())
            ->getFooterSocialNetwork();
    }

    private static function impressum()
    {
        $impressum = (new Option())
            ->getFooterImpressumOptions();

        return [
            'text' => $impressum['text'] ?? null,
            'items' => (new Impressum())->get(),
        ];
    }

    private static function leftBlock($options)
    {
        if (is_null($options)) {
            return [
                'subtitle' => null,
                'paragraph' => null,
                'cta' => [],
            ];
        }

        return [
            'subtitle' => $options['lb_prefooter_left_block_title'],
            'paragraph' => $options['lb_prefooter_left_block_text'],
            'cta' => array_merge($options['lb_prefooter_left_block_cta'], [
                'class' => 'js-gtm-tracking',
                'attributes' => 'data-ga-event="click" data-ga-event-name="cta-prefooter" data-ga-event-value="left"',
                'variants' => ['quaternary'],
            ])
        ];
    }

    private static function centerBlock($options)
    {
        $payload = [];
        $lang = lb_get_current_lang();

        if ($lang != 'it') {
            $payload = [
                'form' => null,
                'cta' => array_merge($options['lb_prefooter_center_block_link_eng'], [
                    'class' => 'js-gtm-tracking',
                    'attributes' => 'data-ga-event="submit" data-ga-event-name="cta-prefooter" data-ga-event-value="center"',
                    'variants' => ['quaternary'],
                ])
            ];
        } else {
            $payload = [
                'form' => [
                    'action' => get_post_type_archive_link('lb-store'),
                    'input' => [
                        'type' => 'search',
                        'id' => "lb-search-val-prefooter",
                        'name' => "lb-search-val",
                        'label' => $options['lb_prefooter_center_block_label'] ?? null,
                        'disabled' => false,
                        'required' => false,
                        'buttonTypeNext' => 'submit',
                        'variants' => ['secondary'],
                    ],
                    'class' => 'js-gtm-tracking',
                    'attributes' => 'data-ga-event="submit" data-ga-event-name="cta-prefooter" data-ga-event-value="center"',
                ],
            ];
        }

        return array_merge([
            'subtitle' => $options['lb_prefooter_center_block_title'] ?? null,
            'paragraph' => $options['lb_prefooter_center_block_text'] ?? null,
        ], $payload);
    }

    private static function rightBlock($options)
    {
        if (is_null($options)) {
            return [
                'subtitle' => null,
                'paragraph' => null,
                'cta' => [],
            ];
        }

        return [
            'subtitle' => $options['lb_prefooter_right_block_title'],
            'paragraph' => $options['lb_prefooter_right_block_text'],
            'cta' => array_merge($options['lb_prefooter_right_block_cta'], [
                'class' => 'js-open-offset-nav js-gtm-tracking',
                'attributes' => 'data-target-offset-nav="lb-newsletter-nav" data-ga-event="click" data-ga-event-name="cta-prefooter" data-ga-event-value="right"',
                'variants' => ['quaternary'],
            ])
        ];
    }

    private static function pictograms()
    {
        $payload = [];
        $lang = wpml_get_current_language();
        $availableCountry = ['BE', 'FR', 'DE', 'IE', 'NL', 'ES'];
        $geoInfo = (new Geolocation($lang))->getInfo();

        if ($lang == 'it' or
            ($lang == 'en' and in_array($geoInfo['countryCode'], $availableCountry))
        ) {
            $payload = [
                [
                    'icon' => ['name' => 'car'],
                    'title' => __('Spedizione e resi gratuiti', 'labo-suisse-theme'),
                ],
                [
                    'icon' => ['name' => 'samples'],
                    'title' => __('Campioni omaggio', 'labo-suisse-theme'),
                ],
                [
                    'icon' => ['name' => 'packaging'],
                    'title' => __('Confezione esclusiva', 'labo-suisse-theme'),
                ],
                [
                    'icon' => ['name' => 'lock'],
                    'title' => __('Pagamento sicuro', 'labo-suisse-theme'),
                ],
            ];
        }

        return $payload;
    }
}
