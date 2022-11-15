<?php

/**
 * The base configuration for WordPress.
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @see https://codex.wordpress.org/Editing_wp-config.php
 */


define('WP_DEBUG', getenv('DEBUG') === 'true');
define('WP_DEBUG_LOG', getenv('DEBUG') === 'true');

define('WP_ENVIRONMENT_TYPE', getenv('WP_ENVIRONMENT_TYPE'));

$dsn = (object) parse_url(getenv('DATABASE_URL'));

define('DB_NAME', substr($dsn->path, 1));
define('DB_USER', $dsn->user);
define('DB_PASSWORD', isset($dsn->pass) ? $dsn->pass : null);
define('DB_HOST', isset($dsn->port) ? "{$dsn->host}:{$dsn->port}" : $dsn->host);

define('W3TC_PRO_DEV_MODE', getenv('W3TC_PRO_DEV_MODE') === 'true');

/* Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/* The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'io{FHkmm5:}z;og&(QoBKF?zcugM9!IdPW#&n3j.p$|+6p11M-48;c!*7kBUYX6;');
define('SECURE_AUTH_KEY',  ')9ln!+i<@5r@Xu3(FyDrOJF1WL#c45gI2-MxauN3 htZ*ZT@L/Nc,nNt!||@d+Rp');
define('LOGGED_IN_KEY',    'L:?Fe}aWdS72i6C<WoSSl)R-S|vtNe+|MQZMw16UA>]#hV ]2PU.3k1T#LzPlm%1');
define('NONCE_KEY',        '*dxr>~|aSTsy&N]ZR{Ba)J_7-x.]|]=74~Z>YkFUIdZC0=5.}H[maO`>#+=2;5Y~');
define('AUTH_SALT',        'n#51{P|3}l8Fra#Hwp+o:>>{2`-Na@p9qM]7|DbyeAUjl&uAM0jahx~`ulh1p}8|');
define('SECURE_AUTH_SALT', 'Ez@8F#n[G3k[fzN;P0~.:,DMLl|b<n +&k9# mCvm~!)u S<=Cw#yA89:dV2E$-@');
define('LOGGED_IN_SALT',   '%9ys_-*-`FeP3rJ4bQs-g`5|>U<7(IL0EU%V(-k`){33h+c!DTo0uh)[h3~OANRm');
define('NONCE_SALT',       'x)+Ag`17q0t|wK.-loWSOYQDZq9:ct5?$qAH&TcQ9++4c},J{|*5?vhy(lKt|z3:');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/*
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */

/* That's all, stop editing! Happy publishing. */
$is_https = false;
$protocol = 'http://';
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $_SERVER['HTTPS'] = 'on';
    $is_https = true;
    $protocol = 'https://';
}

/* SSL */
define('FORCE_SSL_LOGIN', $is_https);
define('FORCE_SSL_ADMIN', $is_https);

define('WP_SITEURL', $protocol . getenv('ADMIN_URL'));
define('WP_HOME', $protocol . (getenv('WITH_FRONTEND') === '1' ? getenv('FRONTEND_URL') : getenv('ADMIN_URL')));

define('ADMIN_URL', getenv('ADMIN_URL'));
define('ADMIN_SITEURL', getenv('ADMIN_URL'));
define('FRONTEND_URL', getenv('WITH_FRONTEND') === '1' ? getenv('FRONTEND_URL') : getenv('ADMIN_URL'));
define('WITH_REDIS', getenv('WITH_REDIS') === '1' ? 1 : 0);

define('ADMIN_COOKIE_PATH', '/wp-admin');
define('COOKIEPATH', '/');
define('SITECOOKIEPATH', '.' . getenv('DOMAIN'));
define('COOKIE_DOMAIN', '.' . getenv('DOMAIN'));
define('COOKIEHASH', md5(getenv('DOMAIN')) );

define('WP_CONTENT_DIR', getenv('WP_CONTENT_CUSTOM_DIR'));
//define('WP_CONTENT_URL', getenv('ADMIN_URL') . "/wp-content-custom");
define('WP_DEFAULT_THEME', 'caffeina-starter');

define('POSTINO_CAFF_SMTP_SECURE', 'tls');
define('POSTINO_CAFF_SMTP_PORT', 25);
define('POSTINO_CAFF_SMTP_SERVER', 'email-smtp.eu-west-1.amazonaws.com');
define('POSTINO_CAFF_SMTP_USER', getenv('POSTINO_CAFF_SMTP_USER'));
define('POSTINO_CAFF_SMTP_PASSWORD', getenv('POSTINO_CAFF_SMTP_PASSWORD'));
define('POSTINO_CAFF_MAIL_SENDER', getenv('POSTINO_CAFF_MAIL_SENDER'));
define('POSTINO_CAFF_MAIL_SENDER_NAME', getenv('POSTINO_CAFF_MAIL_SENDER_NAME'));

/* Keep only the last 4 versions of posts */
define('WP_POST_REVISIONS', 4);

// define('WP_ALLOW_MULTISITE', true);

// Offload Media lite plugin settings
define('AS3CF_SETTINGS', serialize(array(
    'provider' => 'aws',
    'access-key-id' => getenv('OFFLOAD_AWS_ACCESS_ID'),
    'secret-access-key' => getenv('OFFLOAD_AWS_SECRET'),
)));

// Memory limit
define('WP_MEMORY_LIMIT', '512M');
define('WP_MAX_MEMORY_LIMIT', '512M');
@ini_set('post_max_size' , '512M' );
@ini_set('upload_max_size' , '512M' );

// Disable Auto Updates
define('WP_AUTO_UPDATE_CORE', false);
define('AUTOMATIC_UPDATER_DISABLED', true);
define('DISABLE_WP_CRON', getenv('DISABLE_WP_CRON') === 'true');
