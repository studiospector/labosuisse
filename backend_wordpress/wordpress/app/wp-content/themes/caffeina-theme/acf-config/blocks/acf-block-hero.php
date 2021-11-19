<?php

add_action('acf/init', 'lb_init_block_hero');
function lb_init_block_hero() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-hero',
            'title'             => __(' Hero'),
            'description'       => __('A custom  Hero block.'),
            'render_template'   => 'gutenberg-blocks/hero.php',
            'category'          => 'caffeina-theme',
            'icon'              => 'admin-comments',
            'keywords'          => array('hero', 'hero'),
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
