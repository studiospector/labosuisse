<?php

namespace Caffeina\LaboSuisse\Blocks;

class LatestNews extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);
        $items = [];

        $query = new \WP_Query(['posts_per_page' => 4]);

        while ($query->have_posts()) {
            $query->the_post();

            $items[] = [
                'images' => [
                    'original' => get_the_post_thumbnail_url(),
                    'large' => get_the_post_thumbnail_url(),
                    'medium' => get_the_post_thumbnail_url(),
                    'small' => get_the_post_thumbnail_url(),
                ],
                'date' => get_the_date("d/m/Y"),
                'infobox' => [
                    'subtitle' =>  get_the_title(),
                    'paragraph' => get_the_excerpt(),
                    'cta' => [
                        'title' => __("Leggi l'articolo", "labo-suisse-theme"),
                        'url' => get_permalink(),
                        'iconEnd' => ['name' => 'arrow-right'],
                        'variants' => ['quaternary']
                    ]
                ],
                'variants' => ['type-2'],
            ];
        }

        $payload = [
            'title' =>  get_field('lb_block_latest_news_title'),
            'cta' => [
                'title' => __("Vai a news e media", "labo-suisse-theme"),
                'url' => get_post_type_archive_link('post'),
                'variants' => ['tertiary']
            ],
            'items' => $items,
        ];

        $this->setContext($payload);
    }
}
