<?php

add_action('acf/init', 'lb_init_block_carousel_post');
function lb_init_block_carousel_post() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-carousel-posts',
            'title'             => __('Carousel Posts'),
            'description'       => __('A custom Carousel Posts block.'),
            'render_template'   => 'gutenberg-blocks/carousel-posts.php',
            'category'          => 'caffeina-theme',
            'icon'              => 'admin-comments',
            'keywords'          => array('carousel', 'posts'),
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
