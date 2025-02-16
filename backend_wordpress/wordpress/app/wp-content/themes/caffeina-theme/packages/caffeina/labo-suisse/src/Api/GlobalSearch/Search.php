<?php

namespace Caffeina\LaboSuisse\Api\GlobalSearch;

use Caffeina\LaboSuisse\Resources\Posts\Brand;
use Caffeina\LaboSuisse\Resources\Posts\Faq;
use Caffeina\LaboSuisse\Resources\Posts\Magazine;
use Caffeina\LaboSuisse\Resources\Posts\Product;
use Timber\Timber;
use WP_Query;

class Search
{
    private $search;
    private $count = 0;

    public function get()
    {
        $items = [];

        if ($this->search) {
            $items = [
                $this->products(),
                $this->brands(),
                $this->faq(),
                $this->posts(),
            ];
        }
        return [
            'count' => $this->count,
            'items' => $items
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
            ++$this->count;
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
                'label' => __('FAQ', 'labo-suisse-theme'),
                'count' => 0
            ],
            'entries' => []
        ];

        foreach ($this->getArchive('lb-faq') as $item) {
            ++$faq['head']['count'];
            ++$this->count;
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
            ++$this->count;
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

        $products = $this->getArchive('product', [
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'tax_query' => [
                [
                    'taxonomy' => 'product_visibility',
                    'terms' => ['exclude-from-catalog'],
                    'field' => 'name',
                    'operator' => 'NOT IN',
                ],
                [
                    'taxonomy' => 'product_visibility',
                    'terms' => ['exclude-from-search'],
                    'field' => 'name',
                    'operator' => 'NOT IN',
                ]]
        ]);

        foreach ($products as $item) {
            ++$counter;
            ++$this->count;
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

    private function getArchive($type, $args = [])
    {
        $query = array_merge($args, [
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'post_type' => $type,
            // 'title_like' => $this->search
            's' => $this->search
        ]);

        // add_filter('posts_where', function ($where, $wp_query) {
        //     global $wpdb;
        //     if ($search_term = $wp_query->get('title_like')) {
        //         $string = esc_sql($wpdb->esc_like($search_term));
        //         $where .= " AND {$wpdb->posts}.post_title LIKE '%{$string}%'";
        //     }
        //     return $where;
        // }, 10, 2);

        $query = new WP_Query($query);

        // remove_filter('posts_where', 'title_filter', 10, 2);

        return $query->get_posts();
    }
}
