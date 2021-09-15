#!/bin/sh
LETSENCRYPT_HOST=letsencrypt \
LETSENCRYPT_EMAIL=letsencrypt \
CI_REGISTRY=localhost \
CI_COMMIT_REF_NAME=development \
CI_PROJECT_PATH=labo-website-2021 \
CI_REGISTRY_IMAGE=localhost/main/labo-suisse/labo-website-2021 \
COMPOSE_PROJECT_NAME=labo-website-2021 \
VIRTUAL_HOST=labo-website-2021 \
docker-compose --env-file ./.env \
  $@
