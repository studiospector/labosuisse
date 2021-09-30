<?php

// fix pagination with permalinks category/postname
function caffeina_pre_pagination_permalink($query)
{
    if ($query->is_main_query() && !$query->is_feed() && !is_admin() && is_category()) {
        $query->set('paged', str_replace('/', '', get_query_var('page')));
    }
}

add_action('pre_get_posts', 'caffeina_pre_pagination_permalink');

function caffeina_pagination_permalink($query_string)
{
    if (isset($query_string['page'])) {
        if (''!=$query_string['page']) {
            if (isset($query_string['name'])) {
                unset($query_string['name']);
            }
        }
    }
    return $query_string;
}

add_filter('request', 'caffeina_pagination_permalink');
