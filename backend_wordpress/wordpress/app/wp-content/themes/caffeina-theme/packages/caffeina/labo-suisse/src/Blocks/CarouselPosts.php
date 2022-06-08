<?php

namespace Caffeina\LaboSuisse\Blocks;


class CarouselPosts extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);
        $items = [];
        // Carousel data
        if (have_rows('lb_block_carousel_posts')) {
            while (have_rows('lb_block_carousel_posts')) : the_row();
                $cf_post = get_sub_field('lb_block_carousel_posts_item');
                if (!empty($cf_post)) {
                    $items[] = [
                        'images' => lb_get_images(get_post_thumbnail_id($cf_post->ID)),
                        'date' => date("d/m/Y", strtotime($cf_post->post_date)),
                        'infobox' => [
                            'subtitle' => $cf_post->post_title,
                            'paragraph' => $cf_post->post_excerpt,
                            'cta' => [
                                'title' => __("Leggi l'articolo", "labo-suisse-theme"),
                                'url' => get_permalink($cf_post->ID),
                                'iconEnd' => ['name' => 'arrow-right'],
                                'variants' => ['quaternary']
                            ]
                        ],
                        'type' => 'type-2',
                        'variants' => null
                    ];
                }
            endwhile;
        }

        $payload = [
            'leftCard' => [
                'type' => 'type-8',
                'variants' => null
            ],
            'items' => $items,
            'variants' => [get_field('lb_block_carousel_posts_variant')]
        ];

        $this->addInfobox($payload['leftCard']);
        $this->setContext($payload);
    }
}
