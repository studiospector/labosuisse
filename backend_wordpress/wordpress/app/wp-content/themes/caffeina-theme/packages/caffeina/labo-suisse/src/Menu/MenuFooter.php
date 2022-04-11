<?php

namespace Caffeina\LaboSuisse\Menu;

use Caffeina\LaboSuisse\Menu\DiscoverLabo\DiscoverLaboFooter;
use Caffeina\LaboSuisse\Menu\Support\Support;
use Caffeina\LaboSuisse\Option\Option;

class MenuFooter
{
    public static function get()
    {
        return [
            'discover' => (new DiscoverLaboFooter())->get(),
            'support' => (new Support())->get(),
            'search' => self::search(),
            'newsletter' => self::newsletter(),
            'social' => self::social()
        ];
    }

    private static function search()
    {
        $search = (new Option())
            ->getFooterSearchOptions();

        return [
            'title' => $search['title'],
            'input' => [
                // The only parameter to manage in options is label
                'type' => 'search',
                'name' => "lb-search-store-footer",
                'label' => $search['label'],
                'disabled' => false,
                'required' => false,
                'buttonTypeNext' => 'button',
                'variants' => ['secondary'],
            ]
        ];
    }

    private static function newsletter()
    {
        $newsletter = (new Option())
            ->getFooterNewsletterOptions();

        return [
            'title' => $newsletter['title'],
            'text' => $newsletter['text'],
            'cta' => $newsletter['cta']
        ];
    }

    private static function social()
    {
        return (new Option())
            ->getFooterSocialNetwork();
    }
}
