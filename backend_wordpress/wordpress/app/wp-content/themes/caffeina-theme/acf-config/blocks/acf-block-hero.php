<?php

add_action('acf/init', 'acf_init_block_hero');
function acf_init_block_hero() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'cf-hero',
            'title'             => __('Hero'),
            'description'       => __('A custom Hero block.'),
            'render_template'   => 'gutenberg-blocks/hero.php',
            'category'          => 'formatting',
            'icon'              => 'admin-comments',
            'keywords'          => array( 'hero', 'quote' ),
        ));
    }
}
