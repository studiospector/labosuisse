<?php

add_action('acf/init', 'lb_init_block_carousel_hero');
function lb_init_block_carousel_hero() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-carousel-hero',
            'title'             => __('Carousel Hero'),
            'description'       => __('Caffeina Block - Carousel Hero.'),
            'render_template'   => 'gutenberg-blocks/carousel-hero.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('carousel', 'hero', 'carousel hero'),
            'example'  => array(
                'attributes' => array(
                    'data' => array(
                        'is_preview' => true
                    )
                )
            )
        ));
    }
}
