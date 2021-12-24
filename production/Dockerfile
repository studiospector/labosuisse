# Frontend stage

ARG CI_REGISTRY
ARG CI_PROJECT_PATH
ARG CI_COMMIT_REF_NAME

FROM ${CI_REGISTRY}/${CI_PROJECT_PATH}/${CI_COMMIT_REF_NAME}/frontend_bundler:latest AS frontend_bundler


# WordPress stage
FROM registry.caffeina.co/devops/nador-images/wordpress-base/no_volume/backend_wordpress:latest AS wordpress

RUN set -xe && curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && chmod +x /usr/local/bin/composer

COPY ./backend_wordpress/wordpress/app/wp-content ${WP_CONTENT_CUSTOM_DIR}
COPY ./backend_wordpress/wordpress/app/wp-config-custom ${WP_CORE_DIR}/wp-config-custom


COPY --from=frontend_bundler /app/dist/public ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme/static
COPY ./frontend_bundler/tommy/optimized ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme/assets

RUN cd ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme && composer install

COPY ./frontend_static/client/app/views ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme/views




# trafex stage (wrap it)

FROM trafex/php-nginx:latest

USER root
RUN apk --no-cache add \
  php8-exif \
  php8-fileinfo \
  php8-zip \
  php8-iconv \
  php8-pecl-imagick \
  php8-simplexml \
  php8-xmlwriter \
  php8-tokenizer \
  php8-pdo_mysql

#COPY ./laravel/docker/entrypoint.sh /usr/local/bin

RUN chmod +x /usr/local/bin/entrypoint.sh
USER nobody

# Install composer from the official image
COPY --from=composer /usr/bin/composer /usr/bin/composer

#COPY  ./laravel/docker/laravel.conf /etc/nginx/conf.d/
COPY ./backend_wordpress/nginx/config/sites-enabled/wordpress.conf /etc/nginx/sites-enabled/
COPY ./backend_wordpress/nginx/config/nginx.conf /etc/nginx/

EXPOSE 80

HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1/fpm-ping

## Copy same files as wordpress image
COPY --chown=nobody --from=wordpress /var/www/html /var/www/html

# Run composer install to install the dependencies
#RUN composer install --optimize-autoloader --no-interaction --no-progress

ENTRYPOINT [ "/usr/local/bin/entrypoint.sh" ]

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
