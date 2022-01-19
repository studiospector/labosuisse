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
# FROM registry.caffeina.co/devops/nador-images/docker-php-nginx/php-nginx
FROM trafex/php-nginx:latest

COPY --from=wordpress --chown=nobody /var/www/html /var/www/html