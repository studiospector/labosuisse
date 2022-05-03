<?php

namespace Caffeina\LaboSuisse\Api\GlobalSearch;

use Caffeina\LaboSuisse\Resources\Posts\Brand;
use Caffeina\LaboSuisse\Resources\Posts\Faq;
use Caffeina\LaboSuisse\Resources\Posts\Magazine;
use Caffeina\LaboSuisse\Resources\Posts\Product;
use Timber\Timber;

class Search
{
    private $search;

    public function get()
    {
        return [
            $this->posts(),
            $this->faq(),
            $this->brands(),
            $this->products()
        ];
    }

    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    private function posts()
    {
        $items = [];
        $post = [
            'id' => 'post',
            'head' => [
                'label' => __('Articoli', 'labo-suisse-theme'),
                'count' => 0
            ],
            'entries' => []
        ];

        foreach ($this->getArchive('post') as $item) {
            ++$post['head']['count'];
            $items[] = (new Magazine($item))->toArray();
        }

        $post['entries'][] = [
            'type' => 'cards-grid-default',
            'data' => [
                'col' => 3,
                'items' => $items,
            ]
        ];

        return $post;
    }

    private function faq()
    {
        $items = [];
        $faq = [
            'id' => 'faq',
            'head' => [
                'label' => __('Faq', 'labo-suisse-theme'),
                'count' => 0
            ],
            'entries' => []
        ];

        foreach ($this->getArchive('lb-faq') as $item) {
            ++$faq['head']['count'];
            $items[] = (new Faq($item))->toArray();
        }

        $faq['entries'][] = [
            'type' => 'cards-grid-default',
            'data' => [
                'col' => 4,
                'items' => $items,
            ]
        ];

        return $faq;
    }

    private function brands()
    {
        $items = [];
        $brand = [
            'id' => 'brand',
            'head' => [
                'label' => __('Brand', 'labo-suisse-theme'),
                'count' => 0
            ],
            'entries' => []
        ];

        $brands = lb_get_brands(['name__like' => $this->search]);

        foreach ($brands as $item) {
            ++$brand['head']['count'];
            $items[] = (new Brand($item))->toArray();
        }

        $brand['entries'][] = [
            'type' => 'cards-grid-default',
            'data' => [
                'col' => 4,
                'items' => $items,
            ]
        ];

        return $brand;
    }

    private function products()
    {
        $counter = 0;

        $items = [];
        foreach ($this->getArchive('product') as $item) {
            ++$counter;

            $brand = get_the_terms($item->ID, 'lb-brand')[0] ?? null;

            if (!isset($items[$brand->term_id])) {
                $items[$brand->term_id]['brand_card'] = Product::brandCard($brand);
            }

            $items[$brand->term_id]['products'][] = Timber::get_post($item->ID);
        }

        return [
            'id' => 'product',
            'head' => [
                'label' => __('Prodotti', 'labo-suisse-theme'),
                'count' => $counter
            ],
            'entries' => [
                [
                    'type' => 'cards-grid-product-ordered',
                    'data' => [
                        'items' => $items
                    ]
                ]
            ]
        ];
    }

    private function getArchive($type)
    {
        $query = new \WP_Query([
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'post_type' => $type,
            's' => $this->search
        ]);

        return $query->get_posts();
    }
}
