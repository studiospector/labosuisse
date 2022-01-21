<?php

add_action('acf/init', 'lb_init_block_carousel_banner_alternate');
function lb_init_block_carousel_banner_alternate() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-carousel-banner-alternate',
            'title'             => __('Carousel banner alternate'),
            'description'       => __('A custom Carousel banner alternate block.'),
            'render_template'   => 'gutenberg-blocks/carousel-banner-alternate.php',
            'category'          => 'caffeina-theme',
            'icon'              => 'admin-comments',
            'keywords'          => array('carousel', 'banner-alternate'),
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
