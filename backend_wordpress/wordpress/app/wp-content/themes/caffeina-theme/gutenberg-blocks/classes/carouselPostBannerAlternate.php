
<?php
require_once(__DIR__ . '/baseBlock.php');

use gutenbergBlocks\BaseBlock;

class CarouselPostBannerAlternate extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);
        $slides = [];

        if (have_rows('lb_block_carousel_post_banner_alternate')) {
            while (have_rows('lb_block_carousel_post_banner_alternate')) {
                the_row();
                $cf_post = get_sub_field('lb_block_carousel_post_banner_alternate_item');

                $slides[] = [
                    'images' => [
                        'original' => wp_get_attachment_url(get_post_thumbnail_id($cf_post->ID)),
                        'large' => wp_get_attachment_url(get_post_thumbnail_id($cf_post->ID)),
                        'medium' => wp_get_attachment_url(get_post_thumbnail_id($cf_post->ID)),
                        'small' => wp_get_attachment_url(get_post_thumbnail_id($cf_post->ID))
                    ],
                    'infobox' => [
                        'date' => date("d/m/Y", strtotime($cf_post->post_date)),
                        'subtitle' => $cf_post->post_title,
                        'paragraph' => $cf_post->post_excerpt,
                        'cta' => [
                            'title' => __("Leggi l'articolo", "labo-suisse-theme"),
                            'url' => get_permalink($cf_post->ID),
                            'variants' => ['tertiary']
                        ]
                    ],
                    'imageBig' => get_sub_field('lb_block_carousel_post_banner_alternate_img_big'),
                    'variants' => [get_sub_field('lb_block_carousel_post_banner_alternate_variants_lr'), get_sub_field('lb_block_carousel_post_banner_alternate_variants_hcb')],
                ];
            }
        }

        $payload = [
            'slides' => $slides
        ];

        $this->setContext($payload);
    }
}
