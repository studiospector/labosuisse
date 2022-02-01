<?php

add_action('acf/init', 'lb_init_block_carousel_banner_alternate');
function lb_init_block_carousel_banner_alternate() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-carousel-banner-alternate',
            'title'             => __('Carousel Banner Alternate'),
            'description'       => __('Caffeina Block - Carousel Banner Alternate.'),
            'render_template'   => 'gutenberg-blocks/carousel-banner-alternate.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('carousel', 'banner', 'alternate', 'banner alternate', 'carousel banner alternate'),
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
