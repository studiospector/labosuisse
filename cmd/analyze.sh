#!/bin/sh

BUILD=${1:-scripts}

./cmd/pull.sh

./cmd/base.sh \
  -f ./docker/analyze.yml \
  run --rm frontend_bundler_vite \
  && npx webpack-bundle-analyzer ./frontend_bundler_vite/client/app/dist/stat.$BUILD.json
