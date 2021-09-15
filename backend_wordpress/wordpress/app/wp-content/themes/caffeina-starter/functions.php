<?php

// set the options to change
$option = array(
    // we don't want no description
    'blogdescription' => '',
    // disable comments
    'default_comment_status' => 'closed',
    // disable trackbacks
    'use_trackback' => '',
    // disable pingbacks
    'default_ping_status' => 'closed',
    // disable pinging
    'default_pingback_flag' => '',
    // change the permalink structure
    'permalink_structure' => '/%category%/%postname%/',
    // dont use year/month folders for uploads
    // 'uploads_use_yearmonth_folders' => '',
    // don't use those ugly smilies
    // 'use_smilies'                   => ''
);

// change the options!
foreach ($option as $key => $value) {
    update_option($key, $value);
}

// flush rewrite rules because we changed the permalink structure
global $wp_rewrite;
$wp_rewrite->flush_rules();

// delete the default comment, post and page
// wp_delete_comment( 1 );
// wp_delete_post( 1, TRUE );
// wp_delete_post( 2, TRUE );

// we need to include the file below because the activate_plugin() function isn't normally defined in the front-end
include_once ABSPATH.'wp-admin/includes/plugin.php';
// activate pre-bundled plugins
if (file_exists(WP_CONTENT_DIR.'/plugins/aeria/aeria.php')) {
    activate_plugin('aeria/aeria.php');
}
if ((WITH_REDIS || WITH_REDIS === 1) && file_exists(WP_CONTENT_DIR.'/plugins/caffeina-flush-redis/caffeina-flush-redis.php')) {
    activate_plugin('caffeina-flush-redis/caffeina-flush-redis.php');
}
if (file_exists(WP_CONTENT_DIR.'/plugins/malini/malini.php')) {
    activate_plugin('malini/malini.php');
}
if (file_exists(WP_CONTENT_DIR.'/plugins/malini_aeria/malini_aeria.php')) {
    activate_plugin('malini_aeria/malini_aeria.php');
}
if (file_exists(WP_CONTENT_DIR.'/plugins/postino/postino.php')) {
    activate_plugin('postino/postino.php');
}
if (file_exists(WP_CONTENT_DIR.'/plugins/sink/sink.php')) {
    activate_plugin('sink/sink.php');
}
if (file_exists(WP_CONTENT_DIR.'/plugins/trelire/trelire.php')) {
    activate_plugin('trelire/trelire.php');
}

// switch the theme to "Caffeina JSON theme"
switch_theme('caffeina-json');
