<?php

add_action('acf/init', 'lb_init_block_banner_video');
function lb_init_block_banner_video() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-banner-video',
            'title'             => __('Banner Video'),
            'description'       => __('Caffeina Block - Banner Video.'),
            'render_template'   => 'gutenberg-blocks/banner-video.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('banner', 'video', 'banner video'),
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

