<?php

add_action('acf/init', 'lb_init_block_separator');
function lb_init_block_separator() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-separator',
            'title'             => __('Caffeina Separator'),
            'description'       => __('Caffeina Block - Separator.'),
            'render_template'   => 'gutenberg-blocks/separator.php',
            'category'          => 'caffeina-theme',
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

