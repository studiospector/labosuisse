<?php

add_action('acf/init', 'lb_init_block_number_list_with_image');
function lb_init_block_number_list_with_image() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-number-list-with-image',
            'title'             => __('Lista Numeri e Immagine'),
            'description'       => __('Caffeina Block - Lista Numeri e Immagine.'),
            'render_template'   => 'gutenberg-blocks/block-number-list-with-image.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('lista', 'numeri', 'lista numeri e immagine'),
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
