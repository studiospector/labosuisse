ARG CI_REGISTRY
ARG CI_PROJECT_PATH
ARG CI_COMMIT_REF_NAME

FROM ${CI_REGISTRY}/${CI_PROJECT_PATH}/${CI_COMMIT_REF_NAME}/frontend_bundler:latest AS frontend_bundler

FROM registry.caffeina.co/devops/nador-images/wordpress-base/no_volume/backend_wordpress:latest AS wordpress


COPY ./backend_wordpress/wordpress/app/wp-content ${WP_CONTENT_CUSTOM_DIR}
COPY ./backend_wordpress/wordpress/app/wp-config-custom ${WP_CORE_DIR}/wp-config-custom


COPY --from=frontend_bundler /app/dist/public ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme/static
COPY ./frontend_bundler/tommy/optimized ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme/assets

RUN cd ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme && composer install

COPY ./frontend_static/client/app/views ${WP_CONTENT_CUSTOM_DIR}/themes/caffeina-theme/views

FROM registry.caffeina.co/devops/nador-images/nginx-base/master/nginx:latest AS nginx

## Copy same files as wordpress image
COPY --from=wordpress /var/www/html /var/www/html

COPY ./backend_wordpress/nginx/config/sites-enabled/wordpress.conf /etc/nginx/sites-enabled/
COPY ./backend_wordpress/nginx/config/nginx.conf /etc/nginx/

# Development layer
FROM wordpress AS development

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} && pecl install xdebug

RUN echo "zend_extension=xdebug" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo -e "\n\
xdebug.mode = debug\n\
xdebug.start_with_request = yes\n\
xdebug.client_port = 9003\n\
xdebug.client_host=localhost\n\
" >> /usr/local/etc/php/php.ini

# Default layer
FROM wordpress
