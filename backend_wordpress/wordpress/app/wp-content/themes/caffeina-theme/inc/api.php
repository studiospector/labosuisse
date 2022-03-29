<?php

use Caffeina\LaboSuisse\Api\Archive\Archive;
use Caffeina\LaboSuisse\Api\Store\Store;

function get_stores()
{
    return (new Store())->all();
}

function get_archive(WP_REST_Request $request)
{
    $params = $request->get_params();
    $args['tax_query'][] = [
        'taxonomy' => 'lb-post-typology',
        'field' => 'slug',
        'terms' => $_GET['type'],
    ];
    $posts = new Archive($request['type']);

    return $posts->get();
}

add_action( 'rest_api_init', function() {
    register_rest_route('v1', '/stores', [
        'methods' => 'GET',
        'callback' => 'get_stores',
        'permission_callback' => '__return_true'
    ]);

    register_rest_route('v1', '/archives', [
        'methods' => 'GET',
        'callback' => 'get_archive',
        'permission_callback' => '__return_true'
    ]);
});
