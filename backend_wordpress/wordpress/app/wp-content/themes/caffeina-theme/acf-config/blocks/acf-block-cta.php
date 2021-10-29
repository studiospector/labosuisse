<?php

add_action('acf/init', 'lb_init_block_cta');
function lb_init_block_cta() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-cta',
            'title'             => __('Cta'),
            'description'       => __('A custom Cta block.'),
            'render_template'   => 'gutenberg-blocks/cta.php',
            'category'          => 'caffeina-theme',
            'icon'              => 'admin-comments',
            'keywords'          => array('cta'),
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
