<?php

use Caffeina\LaboSuisse\Api\Archive\Archive;
use Caffeina\LaboSuisse\Api\Distributor\Distributor;
use Caffeina\LaboSuisse\Api\Store\Store;

function get_stores()
{
    return (new Store())->all();
}

function get_distributors(WP_REST_Request $request)
{
    return (new Distributor($request))->all();
}

function get_archive(WP_REST_Request $request)
{
    $posts = new Archive($request['postType']);

    return $posts
        ->page($request['page'])
        ->postsPerPage($request['posts_per_page'])
        ->addFilters($request['data'])
        ->get();
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

    register_rest_route('v1', '/distributors', [
        'methods' => 'GET',
        'callback' => 'get_distributors',
        'permission_callback' => '__return_true'
    ]);
});
