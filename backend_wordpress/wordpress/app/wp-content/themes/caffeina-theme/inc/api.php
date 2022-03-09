<?php

use Caffeina\LaboSuisse\Api\Store\Store;

function get_stores()
{
    return (new Store())->all();
}

add_action( 'rest_api_init', function() {
    register_rest_route('v1', '/stores', [
        'methods' => 'GET',
        'callback' => 'get_stores',
        'permission_callback' => '__return_true'
    ]);
});
