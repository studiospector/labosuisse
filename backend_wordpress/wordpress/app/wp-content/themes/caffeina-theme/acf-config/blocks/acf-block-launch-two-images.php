<?php

add_action('acf/init', 'lb_init_block_launch_two_images');
function lb_init_block_launch_two_images() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-launch-two-images',
            'title'             => __('Lancio Due Immagini'),
            'description'       => __('Caffeina Block - Lancio Due Immagini.'),
            'render_template'   => 'gutenberg-blocks/block-launch-two-images.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('lancio', 'due immagini'),
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
