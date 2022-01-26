<?php

add_action('acf/init', 'lb_init_block_infobox');
function lb_init_block_infobox() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-infobox',
            'title'             => __('Infobox'),
            'description'       => __('A custom infobox block.'),
            'render_template'   => 'gutenberg-blocks/infobox.php',
            'category'          => 'caffeina-theme',
            'icon'              => 'admin-comments',
            'keywords'          => array('infobox'),
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
