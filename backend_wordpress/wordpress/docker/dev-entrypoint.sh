#!/bin/sh

cd ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme && composer install -n

# exec docker command
$@
