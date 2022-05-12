<?php

add_action('acf/init', 'lb_init_block_newsletter_subscription');
function lb_init_block_newsletter_subscription() {

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'lb-newsletter-subscription',
            'title'             => __('Iscrizione Newsletter'),
            'description'       => __('Caffeina Block - Newsletter Subscription.'),
            'render_template'   => 'gutenberg-blocks/newsletter-subscription.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('newsletter'),
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
