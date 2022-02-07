<?php

add_action('acf/init', 'lb_init_block_cards_grid');
function lb_init_block_cards_grid() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-cards-grid',
            'title'             => __('Cards Grid'),
            'description'       => __('Caffeina Block - cards grid.'),
            'render_template'   => 'gutenberg-blocks/cards-grid.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('cards_grid'),
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
