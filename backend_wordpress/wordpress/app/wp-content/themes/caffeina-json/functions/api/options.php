<?php

function api_getAllOptions()
{
    if (function_exists('get_aeria_options')) {
        return get_aeria_options();
    } else {
        return [];
    }
}

add_action('rest_api_init', function () {
    register_rest_route('/v1', '/options', array(
      'methods' => 'GET',
      'callback' => 'api_getAllOptions',
    ));
});
