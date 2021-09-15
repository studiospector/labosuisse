FROM node:12-alpine
RUN npm -g install browser-sync

COPY ./docker/utils/wait-for.sh /usr/bin/wait-for
COPY ./frontend_bundler/browser-sync/docker/entrypoint.sh /usr/bin/entrypoint
RUN chmod +x /usr/bin/wait-for

WORKDIR /source

EXPOSE 80
ENTRYPOINT [ "entrypoint" ]
