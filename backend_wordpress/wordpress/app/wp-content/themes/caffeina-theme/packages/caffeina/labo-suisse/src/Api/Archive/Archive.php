<?php

namespace Caffeina\LaboSuisse\Api\Archive;

use Carbon\Carbon;
use Timber\Timber;
use WP_Query;

class Archive
{
    private $postType;
    private $args;

    public function __construct($postType, $filters = [])
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
        switch ($this->postType) {
            case 'post':
                $items = $this->getPost();
                break;
            default :
                $items = [];
                break;
        }

        return $items;

    }

    private function getPost()
    {
        $items = [];

        if(isset($_GET['typology'])) {
            $this->args['tax_query'][] = [
                'taxonomy' => 'lb-post-typology',
                'field' => 'slug',
                'terms' => $_GET['typology'],
            ];
        }

        if(isset($_GET['year'])) {
            $this->args['date_query'][] = [
                'year' => $_GET['year']
            ];
        }

        $posts = (new WP_Query($this->args))->get_posts();

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

            $items[] = Timber::render('@PathViews/components/card.twig', [
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
        }

        return $items;
    }
}
