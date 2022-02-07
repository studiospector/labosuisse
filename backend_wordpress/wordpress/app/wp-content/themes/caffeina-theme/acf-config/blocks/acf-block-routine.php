<?php

add_action('acf/init', 'lb_init_block_routine');
function lb_init_block_routine() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-routine',
            'title'             => __('Routine'),
            'description'       => __('Caffeina Block - routine.'),
            'render_template'   => 'gutenberg-blocks/routine.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('routine'),
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

