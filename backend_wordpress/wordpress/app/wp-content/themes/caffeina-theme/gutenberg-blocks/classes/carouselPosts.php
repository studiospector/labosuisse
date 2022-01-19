<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class CarouselPosts extends BaseBlock {
    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
        $items = [];
        // Carousel data
        if (have_rows('lb_block_carousel_posts')) {
            $items = [];
            while (have_rows('lb_block_carousel_posts')) : the_row();
                $cf_post = get_sub_field('lb_block_carousel_posts_item');
                if (!is_null($cf_post)) {
                    $items[] = [
                        'images' => [
                            'original' => wp_get_attachment_url(get_post_thumbnail_id($cf_post->ID)),
                            'large' => wp_get_attachment_url(get_post_thumbnail_id($cf_post->ID)),
                            'medium' => wp_get_attachment_url(get_post_thumbnail_id($cf_post->ID)),
                            'small' => wp_get_attachment_url(get_post_thumbnail_id($cf_post->ID))
                        ],
                        'date' => $cf_post->post_date,
                        'variants' => ['type-2'],
                        'infobox' => [
                            'subtitle' => $cf_post->title,
                            'paragraph' => $cf_post->post_excerpt,
                            'cta' => [
                                'title' => "Leggi l'articolo",
                                'url' => get_permalink($cf_post->ID),
                                'iconEnd' => ['name' => 'arrow-right'],
                                'variants' => ['quaternary']
                            ]
                        ]
                    ];
                }
            endwhile;
        }

        $payload = [
            'leftCard' => ['variants' => ['type-8']],
            'items' => $items,
            'variants' => ['two-posts']
        ];
        $this->addInfobox($payload['leftCard']);
        $this->setContext($payload);
    }
}

