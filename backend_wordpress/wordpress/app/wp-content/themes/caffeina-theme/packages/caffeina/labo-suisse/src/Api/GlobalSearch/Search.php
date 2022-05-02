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
            $post['entries'][] = [
                'type' => 'infobox',
                'data' => (new Magazine($item))->toArray()
            ];
        }

        return $post;
    }

    private function faq()
    {
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
            $faq['entries'][] = [
                'type' => 'infobox',
                'data' => (new Faq($item))->toArray()
            ];
        }

        return $faq;
    }

    private function brands()
    {
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
            $brand['entries'][] = [
                'type' => 'infobox',
                'data' => (new Brand($item))->toArray()
            ];
        }

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

            $items[$brand->term_id]['product'][] = Timber::get_post($item->ID);
        }

        return [
            'id' => 'product',
            'head' => [
                'label' => __('Prodotti', 'labo-suisse-theme'),
                'count' => $counter
            ],
            'entries' => [
                'type' => 'card-grid-product-ordered',
                'data' => $items
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
