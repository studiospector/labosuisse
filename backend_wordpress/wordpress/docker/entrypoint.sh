#!/bin/sh

set -e
cd ${WP_CONTENT_CUSTOM_DIR}/w3tc-config && php caffeina-override-conf.php

sed s/\$MEMCACHED_URL/${MEMCACHED_URL}/g /etc/nginx/conf.d/wordpress.conf > /tmp/wordpress_new.conf
mv /tmp/wordpress_new.conf /etc/nginx/conf.d/wordpress.conf

cd ${WP_CORE_DIR} && wp w3-total-cache fix_environment nginx

# exec docker command
$@
