<?php

namespace Caffeina\LaboSuisse\Blocks;

class CarouselHero extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);

        $slides = [];

        if (have_rows('lb_block_carousel_hero')) {
            while (have_rows('lb_block_carousel_hero')) : the_row();
                $cta = [];
                if (get_sub_field('lb_block_carousel_hero_infobox_btn') != "") {
                    $cta = array_merge((array)get_sub_field('lb_block_carousel_hero_infobox_btn'), ['variants' => [get_sub_field('lb_block_carousel_hero_infobox_btn_variants')]]);
                }

                $sizes = [
                    // 'lg' => 'lg',
                    // 'md' => 'md-hero',
                    // 'sm' => 'md-hero',
                    // 'xs' => 'md-hero'
                    'lg' => 'lg',
                    'md' => 'md-hero',
                    'sm' => 'md-hero',
                    'xs' => 'md-hero'
                ];

                $slides[] = [
                    'isSubElement' => true,
                    'images' => lb_get_images(get_sub_field('lb_block_carousel_hero_img'), $sizes),
                    'infoboxPosX' => get_sub_field('lb_block_carousel_hero_infoboxposx'),
                    'infoboxPosY' => get_sub_field('lb_block_carousel_hero_infoboxposy'),
                    'container' => get_sub_field('lb_block_carousel_hero_container'),
                    'whiteText' => get_sub_field('lb_block_carousel_hero_text_white'),
                    'variants' => [get_sub_field('lb_block_carousel_hero_variants')],
                    'infobox' => [
                        'tagline' => get_sub_field('lb_block_carousel_hero_infobox_tagline'),
                        'title' => get_sub_field('lb_block_carousel_hero_infobox_title'),
                        'subtitle' => get_sub_field('lb_block_carousel_hero_infobox_subtitle'),
                        'paragraph' => get_sub_field('lb_block_carousel_hero_infobox_paragraph'),
                        //'cta' => array_merge((array)get_sub_field('lb_block_carousel_hero_infobox_btn'),['variants' => [get_sub_field('lb_block_carousel_hero_infobox_btn_variants')]])
                        'cta' => $cta
                    ]
                ];

            endwhile;
        }

        $payload = [
            'slides' => $slides
        ];

        $this->linkToPreload($slides);

        // $this->context['data'] = array_merge($this->context['data'],$infobox);
        $this->setContext($payload);
    }

    public function linkToPreload($slides) {
        
        if (!empty($slides)) {

            $original_img = $slides[0]['images']['original'] ? $slides[0]['images']['original'] : null;
            $md_img = $slides[0]['images']['md'] ? $slides[0]['images']['md'] : null;
    
            add_action('wp_head', function() use ($original_img, $md_img)  {
                if ($original_img) {
                    ?>
                    <link rel="preload" as="image" media="(min-width: 801px)" href="<?php echo $original_img; ?>">
                    <?php
                }

                if ($md_img) {
                    ?>
                    <link rel="preload" as="image" media="(max-width: 800px)" href="<?php echo $md_img; ?>">
                    <?php 
                }
            });
        }
    }
}
