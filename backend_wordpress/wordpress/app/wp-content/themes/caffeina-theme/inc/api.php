<?php

use Caffeina\LaboSuisse\Api\Archive\Archive;
use Caffeina\LaboSuisse\Api\Distributor\Distributor;
use Caffeina\LaboSuisse\Api\GlobalSearch\Autocomplete;
use Caffeina\LaboSuisse\Api\GlobalSearch\Search;
use Caffeina\LaboSuisse\Api\Store\Store;
use Caffeina\LaboSuisse\Api\Multicountry\Geolocation;

function get_stores()
{
    return (new Store())->all();
}

function get_distributors(WP_REST_Request $request)
{
    return (new Distributor($request))->all();
}

function get_autocomplete(WP_REST_Request $request)
{
    $search = $request['search'];

    return (new Autocomplete($search))->get();
}

function get_global_search(WP_REST_Request $request)
{
    $search = new Search();

    return $search
        ->setSearch($request['search'])
        ->get();
}

function get_archive(WP_REST_Request $request)
{
    $posts = (new Archive($request['postType']))
        ->page($request['page'])
        ->postsPerPage($request['posts_per_page'])
        ->addFilters($request['data'])
        ->get();

    return rest_ensure_response($posts);
}

function get_multicountry_geolocation(WP_REST_Request $request)
{
    return (new Geolocation($request))->get();
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

    register_rest_route('v1','/global-search/autocomplete', [
        'methods' => 'GET',
        'callback' => 'get_autocomplete',
        'permission_callback' => '__return_true'
    ]);

    register_rest_route('v1','/global-search', [
        'methods' => 'GET',
        'callback' => 'get_global_search',
        'permission_callback' => '__return_true'
    ]);

    register_rest_route('v1', '/multicountry-geolocation', [
        'methods' => 'GET',
        'callback' => 'get_multicountry_geolocation',
        'permission_callback' => '__return_true'
    ]);
});
