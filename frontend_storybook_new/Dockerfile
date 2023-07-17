FROM node:18.16.0-alpine AS frontend_storybook_new
WORKDIR /app

RUN apk add --no-cache git --update
COPY ./frontend_storybook_new/storybook/docker/entrypoint.sh /usr/bin/entrypoint
COPY ./frontend_storybook_new/storybook/app/package.json ./frontend_storybook_new/storybook/app/package-lock.json ./
COPY ./frontend_storybook_new/storybook/app/.npmrc ./
RUN npm install

COPY ./frontend_storybook_new/storybook/app/ ./
COPY ./frontend_bundler_vite/client/app/src/styles ./styles
COPY ./frontend_bundler_vite/client/app/src/scripts ./scripts
COPY ./frontend_static/client/app/views ./views
COPY ./frontend_static/client/app/fixtures ./fixtures
COPY ./frontend_bundler_vite/tommy/optimized ./public/assets

RUN npm run build

EXPOSE 6006
ENTRYPOINT ["entrypoint"]

FROM nginx:alpine AS frontend_storybook_new_nginx

COPY ./frontend_storybook_new/nginx/config /etc/config
COPY ./frontend_storybook_new/nginx/docker/entrypoint.sh /usr/bin/entrypoint
COPY ./frontend_bundler_vite/tommy/optimized /var/assets
COPY --from=frontend_storybook_new /app/storybook-static /var/static

ENTRYPOINT entrypoint
