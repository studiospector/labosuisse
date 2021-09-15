#!/bin/bash
set -e

mysqladmin \
  -h localhost \
  -uroot \
  -p${MYSQL_ROOT_PASSWORD} \
  create ${BACKEND_WORDPRESS_MYSQL_DATABASE}

mysql \
  -h localhost \
  -uroot \
  -p${MYSQL_ROOT_PASSWORD} \
  -e "CREATE USER '${BACKEND_WORDPRESS_MYSQL_USER}'@'%' IDENTIFIED BY '${BACKEND_WORDPRESS_MYSQL_PASSWORD}';"

mysql \
  -h localhost \
  -uroot \
  -p${MYSQL_ROOT_PASSWORD} \
  -e "GRANT ALL PRIVILEGES ON ${BACKEND_WORDPRESS_MYSQL_DATABASE}.* TO '${BACKEND_WORDPRESS_MYSQL_USER}'@'%';FLUSH PRIVILEGES;"
