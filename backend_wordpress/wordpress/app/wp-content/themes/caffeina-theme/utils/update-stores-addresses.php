<?php

require_once( '/var/www/html/wp-load.php' );

$stores = new WP_Query([
    'post_type' => 'lb-store',
    'posts_per_page' => -1
]);

foreach ($stores->get_posts() as $store) {

    $rawAddress = get_field('lb_stores_gmaps_point', $store->ID);

    update_post_meta($store->ID, 'lb_stores_province', $rawAddress['state_short']);

    echo "{$store->post_title} updated!\n\n";
}
