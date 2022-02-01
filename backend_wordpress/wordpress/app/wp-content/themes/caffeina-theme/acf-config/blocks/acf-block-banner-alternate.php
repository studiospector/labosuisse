<?php

add_action('acf/init', 'lb_init_block_banner_alternate');
function lb_init_block_banner_alternate() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-banner-alternate',
            'title'             => __('Banner Alternate'),
            'description'       => __('Caffeina Block - Banner Alternate.'),
            'render_template'   => 'gutenberg-blocks/banner-alternate.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('banner', 'alternate', 'banner alternate'),
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
