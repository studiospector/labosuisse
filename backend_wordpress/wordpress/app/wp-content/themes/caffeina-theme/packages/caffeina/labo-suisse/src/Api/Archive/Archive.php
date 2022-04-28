<?php

namespace Caffeina\LaboSuisse\Api\Archive;

use Caffeina\LaboSuisse\Resources\Posts\Brand;
use Caffeina\LaboSuisse\Resources\Posts\Magazine;
use Caffeina\LaboSuisse\Resources\Posts\Product;
use Carbon\Carbon;
use Timber\Timber;
use WP_Query;

class Archive
{
    private $postType;
    private $args;

    public function __construct($postType)
    {
        $this->postType = $postType;

        $this->args = [
            'post_status' => 'publish',
            'post_type' => $this->postType,
            'tax_query' => [],
            'date_query' => []
        ];
    }

    public function get()
    {
        if($this->postType == 'brand') {
            return $this->brandArchiveResponse();
        }

        $query = new WP_Query($this->args);
        $totalPosts = $query->post_count;
        $hasPosts = $this->args['paged'] < $query->max_num_pages;

        if($totalPosts === 0) {
            return [
                'totalPosts' => $totalPosts,
                'hasPosts' => $hasPosts,
                'noResult' => $this->noResults(),
            ];
        }

        $callback = str_replace('-','', $this->postType) . "ArchiveResponse";

        $items = $this->$callback(
            $query->get_posts()
        );

        return [
            'totalPosts' => $totalPosts,
            'posts' => $items,
            'hasPosts' => $hasPosts
        ];
    }

    public function page($page)
    {
        $this->args['paged'] = $page;

        return $this;
    }

    public function postsPerPage($postsPerPage)
    {
        $this->args['posts_per_page'] = 4;

        if ($postsPerPage) {
            $this->args['posts_per_page'] = $postsPerPage;
        }

        return $this;
    }

    public function addFilters($filters)
    {
        if (!is_null($filters)) {
            foreach ($filters as $filter) {
                $filter = json_decode($filter);

                if (property_exists($filter, 'taxonomy')) {
                    $this->filterByTaxonomy($filter->taxonomy, $filter->values);
                }

                if (property_exists($filter, 'year')) {
                    $this->filterByYear($filter->values);
                }
            }
        }

        return $this;
    }

    private function filterByTaxonomy($taxonomy, $values)
    {
        $values = array_filter($values);

        if(!empty($values)) {
            $this->args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'terms' => $values,
                'operator' => 'IN'
            ];
        }
    }

    private function filterByYear($years)
    {
        $this->args['date_query']['relation'] = 'OR';

        foreach ($years as $year) {
            $this->args['date_query'][] = [
                'year' => $year
            ];
        }

        return $this;
    }

    private function postArchiveResponse($posts)
    {
        $items = [];

        foreach ($posts as $post) {
            $items[] = (new Magazine($post))->toArray();
        }

        return $items;
    }

    private function productArchiveResponse($posts)
    {
        $items = [];

        foreach ($posts as $post) {
            $brand = get_the_terms($post->ID, 'lb-brand')[0] ?? null;
            if ($brand) {
                if (!isset($items[$brand->term_id])) {
                    $items[$brand->term_id] = [];
                    $items[$brand->term_id]['brand_card'] = Product::brandCard($brand);
                }

                $items[$brand->term_id]['products'][] = Timber::compile('@PathViews/woo/partials/tease-product.twig', ['post' => Timber::get_post($post->ID)]);
            }
        }

        return $items;
    }

    private function lbjobArchiveResponse($posts)
    {
        $items = [];

        foreach ($posts as $post) {
            // Job locations
            $jobLocation = get_job_location();
            $isHeadquarter = $jobLocation['isHeadquarter'];
            $job_location_links = $jobLocation['jobLocationLinks'];

            $items[] = [
                'col_classes' => ['lb-cards-grid__card', 'col-12', 'col-lg-8', 'offset-lg-2'], // Used only in JS for setting col Grid
                'infobox' => [
                    'subtitle' => $post->post_title,
                    'location' => (empty($job_location_links)) ? null : [
                        'isHeadquarter' => $isHeadquarter,
                        'label' => $job_location_links,
                    ],
                    'scope' => [
                        'label' => __('Ambito:', 'labo-suisse-theme'),
                        'value' => get_field('lb_job_scope', $post->ID)
                    ],
                    'paragraph' => get_the_excerpt($post->ID),
                    'cta' => [
                        'url' => get_permalink($post->ID),
                        'title' => __('Leggi di più', 'labo-suisse-theme'),
                        'variants' => ['quaternary']
                    ]
                ],
                'variants' => ['type-9']
            ];
        }

        return $items;
    }

    private function brandArchiveResponse()
    {
        $this->args['post_type'] = 'product';
        $query = new WP_Query($this->args);
        $totalPosts = $query->post_count;
        $hasPosts = $this->args['paged'] < $query->max_num_pages;

        if($totalPosts === 0) {
            return [
                'totalPosts' => $totalPosts,
                'posts' => $this->noResults(),
                'hasPosts' => $hasPosts
            ];
        }

        $brands = [];
        foreach ($query->get_posts() as $post) {
            $brand = get_the_terms($post->ID, 'lb-brand')[0];
            $brand_page = get_field('lb_brand_page', $brand);

            if(isset($brands[$brand->term_id])) {
                continue;
            }
            $brands[$brand->term_id] = (new Brand($brand))->toArray();
            $brands[$brand->term_id] = [
                'col_classes' => ['col-12', 'col-md-4'], // Used only in JS for setting col Grid
                'images' => lb_get_images(get_field('lb_brand_image', $brand)),
                'infobox' => [
                    'subtitle' => $brand->name,
                    'paragraph' => $brand->description,
                    'cta' => !empty($brand_page) ? [
                        'url' => get_permalink($brand_page),
                        'title' => __('Vai al brand', 'labo-suisse-theme'),
                        'variants' => ['quaternary']
                    ] : null
                ],
                'variants' => ['type-10']
            ];
        }

        return [
            'totalPosts' => $totalPosts,
            'posts' => array_values($brands),
            'hasPosts' => $hasPosts
        ];
    }

    private function noResults()
    {
        return [
            'title' => __('Nessun risultato trovato', 'labo-suisse-theme'),
            'paragraph' => __('Siamo spiacenti! non riusciamo a trovare nessun risultato che corrisponda alla tua ricerca.', 'labo-suisse-theme')
        ];
    }
}
