ARG CI_REGISTRY
ARG CI_PROJECT_PATH
ARG CI_COMMIT_REF_NAME

FROM ${CI_REGISTRY}/${CI_PROJECT_PATH}/${CI_COMMIT_REF_NAME}/frontend_bundler:latest AS frontend_bundler

FROM registry.caffeina.co/devops/nador-images/wordpress-base/master/backend_wordpress

RUN set -xe && curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && chmod +x /usr/local/bin/composer

COPY ./backend_wordpress/wordpress/app/wp-content /root/src/wp-content
COPY ./backend_wordpress/wordpress/app/wp-config-custom /root/src/wp-config-custom

COPY --from=frontend_bundler /app/dist/public /root/src/wp-content/themes/caffeina-theme/static
COPY ./frontend_bundler/tommy/optimized /root/src/wp-content/themes/caffeina-theme/assets

RUN cd /root/src/wp-content/themes/caffeina-theme && composer install
