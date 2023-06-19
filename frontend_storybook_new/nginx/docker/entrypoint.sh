#!/bin/sh

ln -sf /etc/config/base.conf /etc/nginx/conf.d/default.conf
nginx -g 'daemon off;'
