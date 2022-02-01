<?php

add_action('acf/init', 'lb_init_block_image_card');
function lb_init_block_image_card() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-image-card',
            'title'             => __('Immagine e Card'),
            'description'       => __('Caffeina Block - Immagine e Card.'),
            'render_template'   => 'gutenberg-blocks/block-image-card.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('immagine', 'card', 'immagine e card'),
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
