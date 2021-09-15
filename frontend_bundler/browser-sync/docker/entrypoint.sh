#!/bin/sh
wait-for \
  frontend_bundler:15672 \
  -t 600 \
  -- browser-sync start \
    --config /source/config/browser-sync.js
