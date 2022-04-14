<?php

namespace Caffeina\LaboSuisse\Api\Archive;

use Caffeina\LaboSuisse\Resources\Distributor as DistributorResource;
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
            return json_encode([
                'totalPosts' => $totalPosts,
                'posts' => $this->noResults(),
                'hasPosts' => $hasPosts
            ]);
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
            $variant = 'type-2';
            $image = null;
            $cta_title = __("Leggi l'articolo", "labo-suisse-theme");

            $typology = get_field('lb_post_typology', $post->ID);

            if ($typology == 'press') {
                $variant = 'type-6';
                $image = get_field('lb_post_press_logo', $post->ID);
                $cta_title = __("Visualizza", "labo-suisse-theme");
            }

            $card_content = Timber::compile('@PathViews/components/card.twig', [
                'images' => lb_get_images(get_post_thumbnail_id($post->ID)),
                'date' => Carbon::createFromDate($post->post_date)->format('d/m/Y'),
                'variants' => [$variant],
                'infobox' => [
                    'image' => $image,
                    'subtitle' => $post->post_title,
                    'paragraph' => $post->post_excerpt,
                    'cta' => [
                        'title' => $cta_title,
                        'url' => get_permalink($post->ID),
                        'iconEnd' => ['name' => 'arrow-right'],
                        'variants' => ['quaternary']
                    ]
                ],
            ]);

            $items[] = "<div class=\"col-12 col-md-3\">$card_content</div>";
        }

        return $items;
    }

    private function productArchiveResponse($posts)
    {
        $items = [];
        foreach ($posts as $post) {
            $brand = get_the_terms($post->ID, 'lb-brand');

            if ($brand) {
                $brand = $brand[0];

                if (!isset($items[$brand->term_id])) {
                    $items[$brand->term_id] = [];
                    $items[$brand->term_id]['brand_card'] = [
                        'color' => get_field('lb_brand_color', $brand),
                        'infobox' => [
                            'subtitle' => $brand->name,
                            'paragraph' => $brand->description,
                            'cta' => [
                                'url' => get_term_link($brand),
                                'title' => __('Scopri il brand', 'labo-suisse-theme'),
                                'variants' => ['quaternary']
                            ]
                        ],
                        'variants' => ['type-8']
                    ];
                }

                $items[$brand->term_id]['products'][] = Timber::get_post($post->ID);
            }
        }

        return [Timber::compile('@PathViews/components/cards-grid-product-ordered.twig', ['items' => $items])];
    }

    private function lbjobArchiveResponse($posts)
    {
        $items = [];

        foreach ($posts as $post) {
            // Job locations
            $jobLocation = get_job_location();
            $isHeadquarter = $jobLocation['isHeadquarter'];
            $job_location_links = $jobLocation['jobLocationLinks'];

            $card_content = Timber::compile('@PathViews/components/card.twig', [
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
                    'paragraph' => $post->post_excerpt,
                    'cta' => [
                        'url' => get_permalink($post->ID),
                        'title' => __('Leggi di più', 'labo-suisse-theme'),
                        'variants' => ['quaternary']
                    ]
                ],
                'variants' => ['type-9']
            ]);

            $items[] = '<div class="lb-cards-grid__card col-12 col-lg-8 offset-lg-2">' . $card_content . '</div>';
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
            return json_encode([
                'totalPosts' => $totalPosts,
                'posts' => $this->noResults(),
                'hasPosts' => $hasPosts
            ]);
        }

        $brands = [];
        foreach ($query->get_posts() as $post) {
            $brand = get_the_terms($post->ID, 'lb-brand')[0];
            $brand_page = get_field('lb_brand_page', $brand);

            if(isset($brands[$brand->term_id])) {
                continue;
            }

            $card = Timber::compile('@PathViews/components/card.twig', [
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
            ]);

            $brands[$brand->term_id] = "<div class=\"col-12 col-md-4\">$card</div>";
        }

        return json_encode([
            'totalPosts' => $totalPosts,
            'posts' => array_values($brands),
            'hasPosts' => $hasPosts
        ]);
    }

    private function lbDistributorArchiveResponse($posts)
    {
        $distributors = [];

        foreach ($posts as $post) {
            $distributors[] = (new DistributorResource($post))->toArray();
        }

        return $distributors;
    }

    private function noResults()
    {
        return '<div class="col-12">
            <div class="lb-no-results">
                <div class="infobox__title h2">'. __('Nessun risultato trovato', 'labo-suisse-theme') .'</div>
                <p class="infobox__paragraph">'. __('Siamo spiacenti! non riusciamo a trovare nessun risultato che corrisponda alla tua ricerca.', 'labo-suisse-theme') .'</p>
            </div>
        </div>';
    }
}
