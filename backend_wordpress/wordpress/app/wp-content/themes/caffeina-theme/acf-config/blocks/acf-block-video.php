<?php

add_action('acf/init', 'lb_init_block_video');
function lb_init_block_video() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-video',
            'title'             => __('Video'),
            'description'       => __('Caffeina Block - Video.'),
            'render_template'   => 'gutenberg-blocks/video.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('video'),
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

