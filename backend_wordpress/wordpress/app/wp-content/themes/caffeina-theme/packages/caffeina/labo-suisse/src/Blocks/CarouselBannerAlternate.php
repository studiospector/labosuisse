<?php

namespace Caffeina\LaboSuisse\Blocks;

class CarouselBannerAlternate extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);

        $slides = [];

        if (have_rows('lb_block_carousel_banner_alternate')) {
            while (have_rows('lb_block_carousel_banner_alternate')) : the_row();
                $cta = [];
                if (get_sub_field('lb_block_carousel_banner_alternate_infobox_btn') != "") {
                    $cta = array_merge((array)get_sub_field('lb_block_carousel_banner_alternate_infobox_btn'), ['variants' => [get_sub_field('lb_block_carousel_banner_alternate_infobox_btn_variants')]]);
                }

                $slides[] = [
                    'images' => lb_get_images(get_sub_field('lb_block_carousel_banner_alternate_img')),
                    'noContainer' => true,
                    'infobox' => [
                        'tagline' => get_sub_field('lb_block_carousel_banner_alternate_infobox_tagline'),
                        'title' => get_sub_field('lb_block_carousel_banner_alternate_infobox_title'),
                        'subtitle' => get_sub_field('lb_block_carousel_banner_alternate_infobox_subtitle'),
                        'paragraph' => get_sub_field('lb_block_carousel_banner_alternate_infobox_paragraph'),
                        'cta' => $cta
                    ],
                    'imageBig' => get_sub_field('lb_block_carousel_banner_alternate_img_big'),
                    'variants' => [get_sub_field('lb_block_carousel_banner_alternate_variants_lr'), get_sub_field('lb_block_carousel_banner_alternate_variants_hcb')],
                ];

            endwhile;
        }

        $payload = [
            'slides' => $slides
        ];

        $this->setContext($payload);
    }
}
