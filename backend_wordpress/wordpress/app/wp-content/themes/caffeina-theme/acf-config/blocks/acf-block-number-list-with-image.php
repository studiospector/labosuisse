<?php

add_action('acf/init', 'lb_init_block_number_list_with_image');
function lb_init_block_number_list_with_image() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-number-list-with-image',
            'title'             => __('block-number-list-with-image'),
            'description'       => __('A custom block-number-list-with-image block.'),
            'render_template'   => 'gutenberg-blocks/block-number-list-with-image.php',
            'category'          => 'caffeina-theme',
            'icon'              => 'admin-comments',
            'keywords'          => array('block-number-list-with-image'),
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
