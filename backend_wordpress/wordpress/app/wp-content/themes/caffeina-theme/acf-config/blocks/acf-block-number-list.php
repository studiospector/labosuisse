<?php

add_action('acf/init', 'lb_init_block_number_list');
function lb_init_block_number_list() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-number-list',
            'title'             => __('block-number-list'),
            'description'       => __('A custom block-number-list block.'),
            'render_template'   => 'gutenberg-blocks/block-number-list.php',
            'category'          => 'caffeina-theme',
            'icon'              => 'admin-comments',
            'keywords'          => array('block-number-list'),
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
