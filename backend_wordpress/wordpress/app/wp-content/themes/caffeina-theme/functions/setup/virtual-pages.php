<?php

function plugin_set_query_var($vars)
{
    array_push($vars, 'pool_on_post_type');
    array_push($vars, 'task');
    array_push($vars, 'single_slug');

    return $vars;
}

function plugin_add_rewrite_rule()
{
    add_rewrite_rule('^tasks/([^/]*)/([^/]*)/?', 'index.php?pool_on_post_type=1&task=$matches[1]&single_slug=$matches[2]', 'top');
}

function plugin_include_template($template)
{
    if (get_query_var('pool_on_post_type')) {
        $custom_template = get_theme_root().'/caffeina-json/template-pool-on-post.php';
        if (file_exists($custom_template)) {
            $template = $custom_template;
        }
    }

    return $template;
}

function custom_query_virtual_page($query)
{
    if ($query->is_home()
    && $query->is_main_query()
    && get_query_var('pool_on_post_type')) {
        $query->set('post_name__in', [get_query_var('task')]);
        $query->set('post_type', 'tasks');
    }
}

add_action('query_vars', 'plugin_set_query_var');
add_action('init', 'plugin_add_rewrite_rule');
add_filter('template_include', 'plugin_include_template');
add_action('pre_get_posts', 'custom_query_virtual_page');
