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
FROM wordpress:5-php7.4-fpm-alpine AS wordpress-base

WORKDIR /wordpress_dir
RUN cp -r /var/www/html /wordpress_dir
RUN cp /bin/sh /usr/local/sbin/php-fpm
RUN /usr/local/bin/docker-entrypoint.sh php-fpm || true

FROM wordpress:5-php7.4-fpm-alpine AS wordpress

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
FROM trafex/php-nginx:latest

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
USER nobody

# overrides for nginx conf
COPY ./production/config/php.ini /etc/php8/conf.d/custom.ini
COPY ./production/nginx /etc/nginx

# copy final code
COPY --from=wordpress --chown=nobody /var/www/html /var/www/html
