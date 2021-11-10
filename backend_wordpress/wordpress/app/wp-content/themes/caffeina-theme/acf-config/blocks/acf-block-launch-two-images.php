<?php

add_action('acf/init', 'lb_init_block_launch_two_images');
function lb_init_block_launch_two_images() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-launch-two-images',
            'title'             => __('block-launch-two-images'),
            'description'       => __('A custom block-launch-two-images block.'),
            'render_template'   => 'gutenberg-blocks/block-launch-two-images.php',
            'category'          => 'caffeina-theme',
            'icon'              => 'admin-comments',
            'keywords'          => array('block-launch-two-images'),
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
