<?php

use Timber\Timber;

$items = [];
$today = date("Ymd",mktime(0,0,0,date("m"),date("d"),date("Y")));


$stores = [];

if(isset($_GET['city'])) {
    $stores = new \WP_Query([
        'post_status' => 'publish',
        'post_type' => 'lb-store',
        'fields' => 'ids',
        'meta_query' => [
            [
                'key' => 'lb_stores_gmaps_point',
                'value' => '"' . $_GET['city'] . '"',
                'compare' => 'LIKE'
            ]
        ]
    ]);
    $stores = $stores->get_posts();
}

$args = [
    'post_status' => 'publish',
    'post_type' => 'lb-beauty-specialist',
    'meta_query' => [[
        'key' => 'lb_beauty_specialist_store',
        'value' => $stores,
        'compare' => 'IN'
    ]],
    'meta_key' => 'lb_beauty_specialist_date',
    'orderby' => 'lb_beauty_specialist_date',
    'order' => 'DESC'
];


if(!isset($_GET['show_expired'])) {
    $args['meta_query'][] = [
        'key' => 'lb_beauty_specialist_date',
        'value' => $today,
        'compare' => '>='
    ];
}

$query = new \WP_Query($args);

foreach ($query->get_posts() as $post) {
    $store = get_post(get_field('lb_beauty_specialist_store', $post->ID));
    $date = get_field('lb_beauty_specialist_date', $post->ID);

    $items[$date][]= [
        'store_id' => get_field('lb_beauty_specialist_store', $post->ID),
        'expired' => strtotime($date) < strtotime('now'),
        'date' => $date,
        'store' => $store->post_title,
        'address' => $store->post_content,
        'phone' => get_field('lb_stores_phone_number', $store->ID),
    ];
}
wp_reset_postdata();

ksort($items);
$context = [
    'items' => $items,
];
echo json_encode($items);
//Timber::render('@PathViews/', $context);


