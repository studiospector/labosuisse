<?php

/**
 * Constants
 */

use Caffeina\LaboSuisse\Resources\Woocommerce\OrderExport;

if (!defined('LB_DIR_PATH')) {
	define('LB_DIR_PATH', untrailingslashit(get_template_directory()));
}

if (!defined('LB_DIR_URI')) {
	define('LB_DIR_URI', untrailingslashit(get_template_directory_uri()));
}

if (!defined('LB_BUILD_URI')) {
	define('LB_BUILD_URI', untrailingslashit(get_template_directory_uri()) . '/static');
}

if (!defined('LB_BUILD_PATH')) {
	define('LB_BUILD_PATH', untrailingslashit(get_template_directory()) . '/static');
}

if (!defined('LB_BUILD_JS_URI')) {
	define('LB_BUILD_JS_URI', untrailingslashit(get_template_directory_uri()) . '/static');
}

if (!defined('LB_BUILD_JS_DIR_PATH')) {
	define('LB_BUILD_JS_DIR_PATH', untrailingslashit(get_template_directory()) . '/static');
}

if (!defined('LB_BUILD_IMG_URI')) {
	define('LB_BUILD_IMG_URI', untrailingslashit(get_template_directory_uri()) . '/assets/images');
}

if (!defined('LB_BUILD_CSS_URI')) {
	define('LB_BUILD_CSS_URI', untrailingslashit(get_template_directory_uri()) . '/static');
}

if (!defined('LB_BUILD_CSS_DIR_PATH')) {
	define('LB_BUILD_CSS_DIR_PATH', untrailingslashit(get_template_directory()) . '/static');
}

if (!defined('LB_BUILD_LIB_URI')) {
	define('LB_BUILD_LIB_URI', untrailingslashit(get_template_directory_uri()) . '/static/library');
}



/**
 * Includes
 */
include __DIR__ . '/inc/helpers.php';
include __DIR__ . '/inc/theme-setup.php';
include __DIR__ . '/inc/woocommerce-setup.php';
include __DIR__ . '/inc/api.php';
include __DIR__ . '/inc/pmxi-hooks.php';



/**
 * ACF Blocks autoload
 */
$acf_blocks_files = glob(__DIR__ . '/acf-config/blocks/*');
#$files = list_files(__DIR__ . '/acf-config/blocks');
foreach ( $acf_blocks_files as $file ) {
    include_once $file;
}

add_filter('rest_url', function ($url) {
    if(isCli()) {
        return $url;
    }

    $currentHost = "{$_SERVER['HTTP_X_FORWARDED_PROTO']}://{$_SERVER['HTTP_HOST']}";
    $siteUrl = preg_replace('/(http(s)?:\/\/)|(\/.*){1}/', '${1}', site_url());
    $homeUrl = preg_replace('/(http(s)?:\/\/)|(\/.*){1}$/', '${1}', home_url());

    if ( $currentHost != home_url() ) {
        $url = str_replace($homeUrl,$siteUrl , $url);
    }

    return $url;
});

add_filter( 'wp_mail', function ($args) {
    $excludedSubject = [
        'contatto generico',
        'candidatura',
        'candidatura spontanea',
        'richiesta concessionario'
    ];

    $regex = '(' . implode(')|(', $excludedSubject) . ')';

    if (!preg_match("/{$regex}/i", $args['subject'])) {
        $args['headers'] = [
            'Reply-To: <noreply@labosuisse.com>'
        ];
    }

    return $args;
});

if(wp_next_scheduled('daily_orders_export_v2')) {
    wp_clear_scheduled_hook('daily_orders_export_v2');
}

if(!wp_next_scheduled('daily_orders_export')) {
    wp_schedule_event(strtotime('07:00:00'), 'daily', 'daily_orders_export');
}

add_action('daily_orders_export', function(){
    $export = new OrderExport();
    $export->start();
});
