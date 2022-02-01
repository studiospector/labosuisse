<?php

add_action('acf/init', 'lb_init_block_love_labo');
function lb_init_block_love_labo() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-love-labo',
            'title'             => __('Love'),
            'description'       => __('Caffeina Block - Love.'),
            'render_template'   => 'gutenberg-blocks/love-labo.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('love'),
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
