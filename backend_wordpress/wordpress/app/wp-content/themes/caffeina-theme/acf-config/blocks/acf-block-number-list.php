<?php

add_action('acf/init', 'lb_init_block_number_list');
function lb_init_block_number_list() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-number-list',
            'title'             => __('Lista Numeri'),
            'description'       => __('Caffeina Block - Lista Numeri.'),
            'render_template'   => 'gutenberg-blocks/block-number-list.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('lista', 'numeri', 'lista numeri'),
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
