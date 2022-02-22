<?php

add_action('acf/init', 'lb_init_block_carousel_post_banner_alternate');
function lb_init_block_carousel_post_banner_alternate()
{

    if (function_exists('acf_register_block_type')) {

        acf_register_block_type(array(
            'name'              => 'lb-carousel-post-alternate',
            'title'             => __('Carousel Post Banner Alternate'),
            'description'       => __('Caffeina Block - Carousel Post Banner Alternate.'),
            'render_template'   => 'gutenberg-blocks/carousel-post-banner-alternate.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('carousel', 'post banner', 'alternate', 'post banner alternate', 'carousel post banner alternate'),
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
