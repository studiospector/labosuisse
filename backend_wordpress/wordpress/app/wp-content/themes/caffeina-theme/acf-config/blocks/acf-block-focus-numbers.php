<?php

add_action('acf/init', 'lb_init_block_focus_numbers');
function lb_init_block_focus_numbers() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-focus-numbers',
            'title'             => __('focus numbers'),
            'description'       => __('A custom focus numbers block.'),
            'render_template'   => 'gutenberg-blocks/focus-numbers.php',
            'category'          => 'caffeina-theme',
            'icon'              => 'admin-comments',
            'keywords'          => array('focus', 'numbers'),
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
