<?php

add_action('acf/init', 'lb_init_block_information_boxes');
function lb_init_block_information_boxes() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-information-boxes',
            'title'             => __('Information boxes'),
            'description'       => __('Caffeina Block - Information boxes.'),
            'render_template'   => 'gutenberg-blocks/information-boxes.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('information', 'boxes'),
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
