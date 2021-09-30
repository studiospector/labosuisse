FROM registry.caffeina.co/devops/nador-images/wordpress-base/master/backend_wordpress

RUN set -xe && curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && chmod +x /usr/local/bin/composer

COPY ./backend_wordpress/wordpress/app/wp-content /root/src/wp-content
COPY ./backend_wordpress/wordpress/app/wp-config-custom /root/src/wp-config-custom

RUN cd /root/src/wp-content/themes/caffeina-theme && composer install
