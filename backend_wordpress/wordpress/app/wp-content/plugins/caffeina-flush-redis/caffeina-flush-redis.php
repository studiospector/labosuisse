<?php
/**
 * Plugin Name: Caffeina Flush Redis
 * Description: Flush redis cache of content when update it
 * Version:     1.0.0
 * Author:      Alberto Parziale
 * Author URI:  https://lavolpechevola.com/.
 */
defined('ABSPATH') or die('No script kiddies please!');

require __DIR__.'/lib/predis-1.1.1/autoload.php';
Predis\Autoloader::register();

function getClient()
{
    return new Predis\Client(
      array(
        'host' => 'storage_redis',
      )
    );
}

function flushAll()
{
    $redis_prefix = 'wp-';
    $redis = getClient();
    $keys = $redis->keys($redis_prefix.'*');
    foreach ($keys as $key) {
        $redis->del($key);
    }
}

function caffeina_flush_cache_post($new_status, $old_status, $post)
{
    if (!WITH_REDIS || WITH_REDIS !== 1) {
        return;
    }
    $statusToCheck = ['trash', 'publish'];
    if (in_array($new_status, $statusToCheck)) {
        flushAll();
    }
}

function caffeina_flush_cache_menu($nav_menu_selected_id)
{
    if (!WITH_REDIS || WITH_REDIS !== 1) {
        return;
    }
    // flush all because changing a menu invalidate all pages cached that contains this menus
    flushAll();
}
add_action('transition_post_status', 'caffeina_flush_cache_post', 10, 3);
add_action('wp_update_nav_menu', 'caffeina_flush_cache_menu', 10, 1);
add_action('updated_option', 'caffeina_flush_cache_menu', 10, 1);
