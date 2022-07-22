<?php

namespace Caffeina\LaboSuisse\Blocks;

class CardsGrid extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);
        
        $items = [];
        $image_sizes = [];
        $image_full_size_mobile = get_field('lb_block_cards_grid_image_full_size_mobile');

        if ($image_full_size_mobile) {
            $image_sizes = [
                'lg' => 'lg',
                'md' => 'lg',
                'sm' => 'lg',
                'xs' => 'lg'
            ];
        }

        if (have_rows('lb_block_cards_grid_carousel')) {
            $items = [];
            while (have_rows('lb_block_cards_grid_carousel')) : the_row();
                $cta = [];
                if (get_sub_field('lb_block_cards_grid_carousel_infobox_btn') != "") {
                    $cta = array_merge((array)get_sub_field('lb_block_cards_grid_carousel_infobox_btn'), ['variants' => [get_sub_field('lb_block_cards_grid_carousel_infobox_btn_variants')]]);
                }
                $items[] = [
                    'images' => lb_get_images(get_sub_field('lb_block_cards_grid_carousel_img'), $image_sizes),
                    'noContainer' => true,
                    'infobox' => [
                        'tagline' => get_sub_field('lb_block_cards_grid_carousel_infobox_tagline'),
                        'title' => get_sub_field('lb_block_cards_grid_carousel_infobox_title'),
                        'subtitle' => get_sub_field('lb_block_cards_grid_carousel_infobox_subtitle'),
                        'paragraph' => get_sub_field('lb_block_cards_grid_carousel_infobox_paragraph'),
                        'paragraph_small' => get_sub_field('lb_block_cards_grid_carousel_infobox_paragraph_small'),
                        'cta' => $cta
                    ],
                    'type' => get_field('lb_block_cards_grid_cards_variants'),
                    'variants' => null
                ];

            endwhile;
        }
        $ctaCards = [];
        if (get_field('lb_block_cards_grid_btn') != "") {
            $ctaCards = array_merge((array)get_field('lb_block_cards_grid_btn'), ['variants' => [get_field('lb_block_cards_grid_btn_variants')]]);
        }
        $payload = [
            'tagline' => get_field('lb_block_cards_grid_tagline'),
            'title' => get_field('lb_block_cards_grid_title'),
            'paragraph' => get_field('lb_block_cards_grid_paragraph'),
            'cta' => $ctaCards,
            'cards_text_align' => get_field('lb_block_cards_grid_text_align'),
            'items' => $items,
            'variants' => $image_full_size_mobile ? ['image-full-size-mobile'] : null
        ];
        $this->setContext($payload);
    }
}
