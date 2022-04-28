<?php

namespace Caffeina\LaboSuisse\Api\GlobalSearch;

use Caffeina\LaboSuisse\Resources\Posts\Faq;
use Caffeina\LaboSuisse\Resources\Posts\Magazine;
use Caffeina\LaboSuisse\Resources\Posts\Product;
use Timber\Timber;

class Search
{
    private $postType = [
        'post',
        'product',
        'lb-faq',
    ];

    private $search;

    public function get()
    {
        return $this->getArchive();
    }

    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    private function getArchive()
    {
        $items = [];

        $query = new \WP_Query([
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'post_type' => $this->postType,
            's' => $this->search
        ]);

        $posts = $query->get_posts();

        foreach ($posts as $post) {
            switch (get_post_type($post)){
                case 'post':
                   $items['post'][] = (new Magazine($post))->toArray();
                   break;
                case 'lb-faq':
                    $items['faq'][] = (new Faq($post))->toArray();
                    break;
                case 'product':
                    $items = $this->getProducts($post, $items);
                    break;
            }
        }

        return rest_ensure_response($items);
    }

    private function getBrands()
    {
        $brands = lb_get_brands([
            'name__like' => $this->search
        ]);


    }

    /**
     * @param mixed $post
     * @param array $items
     * @return array
     */
    private function getProducts(mixed $post, array $items): array
    {
        $brand = get_the_terms($post->ID, 'lb-brand')[0] ?? null;

        if (!isset($items[$brand->term_id])) {
            $items['products'][$brand->term_id]['brand_card'] = Product::brandCard($brand);
        }

        //TODO: estrarre solo campi necessari
        $items['products'][$brand->term_id]['products'][] = Timber::get_post($post->ID);

        return $items;
    }
}
