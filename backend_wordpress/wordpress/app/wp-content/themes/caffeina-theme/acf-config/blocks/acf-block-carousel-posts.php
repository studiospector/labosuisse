<?php

add_action('acf/init', 'lb_init_block_carousel_post');
function lb_init_block_carousel_post() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-carousel-posts',
            'title'             => __('Carousel Articoli'),
            'description'       => __('Caffeina Block - Carousel Articoli.'),
            'render_template'   => 'gutenberg-blocks/carousel-posts.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('carousel', 'articoli', 'carousel posts', 'carousel articoli'),
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
