<?php

error_reporting(E_ALL);
ini_set('display_errors', getenv('DEBUG') === 'true');

/** Sets up WordPress vars and included files. */
require_once dirname(__FILE__).'/wp-config-custom/config.php';

/* Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__).'/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH.'wp-settings.php';
