<?php

add_action('acf/init', 'lb_init_block_launch_two_cards');
function lb_init_block_launch_two_cards() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-launch-two-cards',
            'title'             => __('block-launch-two-cards'),
            'description'       => __('A custom block-launch-two-cards block.'),
            'render_template'   => 'gutenberg-blocks/block-launch-two-cards.php',
            'category'          => 'caffeina-theme',
            'icon'              => 'admin-comments',
            'keywords'          => array('block-launch-two-cards'),
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
