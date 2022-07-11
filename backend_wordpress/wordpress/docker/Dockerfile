# Frontend stage

FROM node:12-alpine as frontend_bundler

ARG LB_API_TOKEN
ARG LB_API_URL

WORKDIR /app
RUN apk add --no-cache git --update

# Install dependencies
COPY ./frontend_bundler/client/app/package.json ./
COPY ./frontend_bundler/client/app/package-lock.json ./
COPY ./frontend_bundler/client/app/.npmrc ./
RUN npm install

COPY ./frontend_bundler/client/app/ ./

ENV NODE_ENV=production
ENV LB_API_TOKEN=$LB_API_TOKEN
ENV LB_API_URL=$LB_API_URL

RUN npm run build


# WORDPRESS STAGE
FROM wordpress:5.9.3-php7.4-fpm-alpine AS wordpress-base

WORKDIR /wordpress_dir
RUN cp -r /var/www/html /wordpress_dir
RUN cp /bin/sh /usr/local/sbin/php-fpm
RUN /usr/local/bin/docker-entrypoint.sh php-fpm || true

FROM trafex/php-nginx:2.4.0 AS wordpress

ENV WP_CORE_DIR="/var/www/html"
ENV WP_CONTENT_CUSTOM_DIR="/var/www/html/wp-content-custom"

USER root
RUN set -xe && curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && chmod +x /usr/local/bin/composer
USER nobody

COPY --from=wordpress-base --chown=nobody /wordpress_dir $WP_CORE_DIR
COPY --chown=nobody ./backend_wordpress/wordpress/app/wp-content ${WP_CONTENT_CUSTOM_DIR}
RUN cd ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme && composer install -n

COPY --chown=nobody ./backend_wordpress/wordpress/app/wp-config-custom ${WP_CORE_DIR}/wp-config-custom
COPY --chown=nobody ./backend_wordpress/wordpress/app/wp-config.php ${WP_CORE_DIR}/wp-config.php
COPY --chown=nobody --from=frontend_bundler /app/dist/public ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme/static
COPY --chown=nobody ./frontend_bundler/tommy/optimized ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme/assets
COPY --chown=nobody ./frontend_static/client/app/views ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme/views

# trafex stage (wrap it)
# FROM registry.caffeina.co/devops/nador-images/docker-php-nginx/php-nginx
FROM trafex/php-nginx:2.4.0 AS production

ENV WP_CORE_DIR="/var/www/html"
ENV WP_CONTENT_CUSTOM_DIR="/var/www/html/wp-content-custom"

# :poop:
USER root
RUN apk --no-cache add \
  redis \
  php8-exif \
  php8-fileinfo \
  php8-zip \
  php8-iconv \
  php8-pecl-imagick \
  php8-simplexml \
  php8-xmlwriter \
  php8-redis \
  php8-tokenizer \
  libmemcached \
  libmemcached-libs \
  libmemcached-dev \
  php8-pecl-memcached \
  # required by NR agent
  pcre

COPY --from=registry.caffeina.co/devops/newrelic-php-agent-alpine:9.18.1.303 /usr/lib/php8/modules/newrelic.so /usr/lib/php8/modules/newrelic.so
COPY --from=registry.caffeina.co/devops/newrelic-php-agent-alpine:9.18.1.303 /etc/php8/conf.d/newrelic.ini /etc/php8/conf.d/newrelic.ini
COPY --from=registry.caffeina.co/devops/newrelic-php-agent-alpine:9.18.1.303 /usr/bin/newrelic-daemon /usr/bin/newrelic-daemon
COPY ./backend_wordpress/wordpress/docker/entrypoint.sh /entrypoint.sh

USER nobody

# overrides for nginx conf
COPY ./backend_wordpress/config/php.ini /etc/php8/conf.d/custom.ini
COPY --chown=nobody ./backend_wordpress/nginx /etc/nginx

# copy final code
COPY --from=wordpress --chown=nobody /var/www/html /var/www/html

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

FROM production AS dev

USER root

RUN set -xe && curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && chmod +x /usr/local/bin/composer

COPY ./backend_wordpress/wordpress/docker/dev-entrypoint.sh /dev-entrypoint.sh

USER nobody

ENTRYPOINT ["/dev-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

FROM production
