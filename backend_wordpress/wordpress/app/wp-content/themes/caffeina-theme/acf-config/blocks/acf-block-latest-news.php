<?php

add_action('acf/init', 'lb_init_block_latest_news');
function lb_init_block_latest_news()
{

    if (function_exists('acf_register_block_type')) {

        acf_register_block_type(array(
            'name'              => 'lb-latest_news',
            'title'             => __('Ultime Notizie'),
            'description'       => __('Caffeina Block - Latest News.'),
            'render_template'   => 'gutenberg-blocks/block-latest-news.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('latest_news'),
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
