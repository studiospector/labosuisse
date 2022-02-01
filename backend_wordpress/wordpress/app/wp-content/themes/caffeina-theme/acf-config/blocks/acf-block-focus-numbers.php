<?php

add_action('acf/init', 'lb_init_block_focus_numbers');
function lb_init_block_focus_numbers() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-focus-numbers',
            'title'             => __('Focus Numeri'),
            'description'       => __('Caffeina Block - Focus Numeri.'),
            'render_template'   => 'gutenberg-blocks/focus-numbers.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('focus', 'numeri', 'focus numeri'),
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
