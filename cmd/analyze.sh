#!/bin/sh

BUILD=${1:-scripts}

./cmd/pull.sh

./cmd/base.sh \
  -f ./docker/analyze.yml \
  run --rm frontend_bundler \
  && npx webpack-bundle-analyzer ./frontend_bundler/client/app/dist/public/js/stat.$BUILD.json
