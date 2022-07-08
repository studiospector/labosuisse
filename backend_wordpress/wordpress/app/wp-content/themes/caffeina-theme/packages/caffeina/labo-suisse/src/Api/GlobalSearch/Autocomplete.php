<?php

namespace Caffeina\LaboSuisse\Api\GlobalSearch;

class Autocomplete
{
    private $postType = [
        'post',
        'product',
        'lb-faq',
    ];

    private $search = null;

    public function __construct($search)
    {
        $this->search = $search;
    }

    public function get()
    {
        $archive = $this->getArchive();
        $brands = $this->getBrands();

        $values = array_values(
            array_unique(
                array_merge($archive, $brands)
            )
        );

        return array_map(function ($item) {
            return preg_replace('/\s+/', ' ', preg_replace("#<br\s?/?>#", ' ', $item));
        }, $values);
    }

    private function getArchive()
    {
        $query = new \WP_Query([
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'post_type' => $this->postType,
            's' => $this->search
        ]);

        $posts = $query->get_posts();

        return array_map(function ($post) {
            return $post->post_title;
        }, $posts);
    }

    private function getBrands()
    {
        $brands = lb_get_brands([
            'name__like' => $this->search
        ]);

        return array_map(function ($brand) {
            return $brand->name;
        }, $brands);
    }
}

