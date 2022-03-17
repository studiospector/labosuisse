<?php

add_action('acf/init', 'lb_init_block_checklist');
function lb_init_block_checklist()
{

    if (function_exists('acf_register_block_type')) {

        acf_register_block_type(array(
            'name'              => 'lb-checklist',
            'title'             => __('Checklist'),
            'description'       => __('Caffeina Block - Checklist.'),
            'render_template'   => 'gutenberg-blocks/block-checklist.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('checklist'),
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
