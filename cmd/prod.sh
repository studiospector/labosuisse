#!/bin/sh
./cmd/base.sh \
  -f ./docker-compose.prod.yml \
  up $@
