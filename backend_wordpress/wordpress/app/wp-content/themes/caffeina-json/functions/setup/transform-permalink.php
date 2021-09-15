<?php

function transform_permalink($url)
{
    return str_replace(ADMIN_URL, FRONTEND_URL, $url);
}

function transform_post_type_link($url, $post)
{
    // if ('interventionarea' === get_post_type($post->ID)) {
    //     $url = get_home_url().'/#'.basename($url);
    // }

    // Return the value of the URL
    return transform_permalink($url);
}

add_filter('post_link', 'transform_permalink', 10, 1);
add_filter('page_link', 'transform_permalink', 10, 1);
add_filter('post_type_link', 'transform_post_type_link', 10, 2);
