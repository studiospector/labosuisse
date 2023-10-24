<?php

add_action('acf/init', 'lb_init_block_video_text');
function lb_init_block_video_text() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-video-text',
            'title'             => __('Video con Testo'),
            'description'       => __('Caffeina Block - Video con Testo.'),
            'render_template'   => 'gutenberg-blocks/video-text.php',
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

