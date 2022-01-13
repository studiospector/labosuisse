<?php

add_action('acf/init', 'lb_init_block_separator');
function lb_init_block_separator() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-separator',
            'title'             => __('LB Separator'),
            'description'       => __('A custom separator block.'),
            'render_template'   => 'gutenberg-blocks/separator.php',
            'category'          => 'caffeina-theme',
            'icon'              => 'admin-comments',
            'keywords'          => array('separator'),
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

