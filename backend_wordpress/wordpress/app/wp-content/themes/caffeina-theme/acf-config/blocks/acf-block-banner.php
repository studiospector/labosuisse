<?php

add_action('acf/init', 'lb_init_block_banner');
function lb_init_block_banner() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-banner',
            'title'             => __('Banner'),
            'description'       => __('A custom banner block.'),
            'render_template'   => 'gutenberg-blocks/banner.php',
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
