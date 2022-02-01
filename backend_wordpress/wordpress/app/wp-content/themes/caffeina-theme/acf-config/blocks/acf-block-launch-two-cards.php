<?php

add_action('acf/init', 'lb_init_block_launch_two_cards');
function lb_init_block_launch_two_cards() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-launch-two-cards',
            'title'             => __('Lancio Due Card'),
            'description'       => __('Caffeina Block - Lancio Due Card.'),
            'render_template'   => 'gutenberg-blocks/block-launch-two-cards.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('lancio', 'card', 'due card'),
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
