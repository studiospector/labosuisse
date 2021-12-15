<?php

add_action('acf/init', 'lb_init_block_image_card');
function lb_init_block_image_card() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-image-card',
            'title'             => __('block-image-card'),
            'description'       => __('A custom block-image-card block.'),
            'render_template'   => 'gutenberg-blocks/block-image-card.php',
            'category'          => 'caffeina-theme',
            'icon'              => 'admin-comments',
            'keywords'          => array('block-image-card'),
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
