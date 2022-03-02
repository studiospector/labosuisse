# Frontend stage

FROM node:12-alpine as frontend_bundler
WORKDIR /app
RUN apk add --no-cache git --update

# Install dependencies
COPY ./frontend_bundler/client/app/package.json ./
COPY ./frontend_bundler/client/app/package-lock.json ./
RUN npm install

COPY ./frontend_bundler/client/app/ ./

ENV NODE_ENV=production
RUN npm run build


# WORDPRESS STAGE
FROM wordpress:5.8.0-php7.4-fpm-alpine AS wordpress-base

WORKDIR /wordpress_dir
RUN cp -r /var/www/html /wordpress_dir
RUN cp /bin/sh /usr/local/sbin/php-fpm
RUN /usr/local/bin/docker-entrypoint.sh php-fpm || true

FROM wordpress:5.8.0-php7.4-fpm-alpine AS wordpress

ENV WP_CORE_DIR="/var/www/html"
ENV WP_CONTENT_CUSTOM_DIR="/var/www/html/wp-content-custom"

COPY --from=wordpress-base /wordpress_dir $WP_CORE_DIR
COPY ./backend_wordpress/wordpress/app/uploads.ini /usr/local/etc/php/conf.d/uploads.ini
COPY ./backend_wordpress/wordpress/app/wp-content ${WP_CONTENT_CUSTOM_DIR}
COPY ./backend_wordpress/wordpress/app/wp-config-custom ${WP_CORE_DIR}/wp-config-custom
COPY ./backend_wordpress/wordpress/app/wp-config.php ${WP_CORE_DIR}/wp-config.php

RUN set -xe && curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && chmod +x /usr/local/bin/composer

COPY --from=frontend_bundler /app/dist/public ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme/static
COPY ./frontend_bundler/tommy/optimized ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme/assets

RUN cd ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme && composer install

COPY ./frontend_static/client/app/views ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme/views


# trafex stage (wrap it)
# FROM registry.caffeina.co/devops/nador-images/docker-php-nginx/php-nginx
FROM trafex/php-nginx:2.4.0

ENV WP_CORE_DIR="/var/www/html"
ENV WP_CONTENT_CUSTOM_DIR="/var/www/html/wp-content-custom"

# :poop:
USER root
RUN apk --no-cache add \
  php8-exif \
  php8-fileinfo \
  php8-zip \
  php8-iconv \
  php8-pecl-imagick \
  php8-simplexml \
  php8-xmlwriter

RUN \
 curl -L https://download.newrelic.com/php_agent/release/newrelic-php5-9.18.1.303-linux-musl.tar.gz | tar -C /tmp -zx && \
   NR_INSTALL_USE_CP_NOT_LN=1 NR_INSTALL_SILENT=1 /tmp/newrelic-php5-*/newrelic-install install
COPY ./backend_wordpress/wordpress/docker/newrelic.ini /etc/php8/conf.d/newrelic.ini

USER nobody

# overrides for nginx conf
COPY ./backend_wordpress/config/php.ini /etc/php8/conf.d/custom.ini
# COPY ./production/config/php.ini /etc/php8/conf.d/custom.ini
COPY ./backend_wordpress/nginx /etc/nginx
# COPY ./production/nginx /etc/nginx


# copy final code
COPY --from=wordpress --chown=nobody /var/www/html /var/www/html
