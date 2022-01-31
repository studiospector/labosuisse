<?php

add_action('acf/init', 'lb_init_block_carousel_centered');
function lb_init_block_carousel_centered() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-carousel-centered',
            'title'             => __('Carousel centered'),
            'description'       => __('A custom Carousel centered block.'),
            'render_template'   => 'gutenberg-blocks/carousel-centered.php',
            'category'          => 'caffeina-theme',
            'icon'              => 'admin-comments',
            'keywords'          => array('carousel', 'centered'),
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
