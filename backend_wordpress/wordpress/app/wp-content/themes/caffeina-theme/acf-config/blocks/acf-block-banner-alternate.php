<?php

add_action('acf/init', 'lb_init_block_banner_alternate');
function lb_init_block_banner_alternate() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-banner-alternate',
            'title'             => __('Banner alternate'),
            'description'       => __('A custom banner alternate block.'),
            'render_template'   => 'gutenberg-blocks/banner-alternate.php',
            'category'          => 'caffeina-theme',
            'icon'              => 'admin-comments',
            'keywords'          => array('banner'),
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

