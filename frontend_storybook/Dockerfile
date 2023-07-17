FROM node:16.17.1-alpine AS frontend_storybook
WORKDIR /app

RUN apk add --no-cache git --update
COPY ./frontend_storybook/storybook/docker/entrypoint.sh /usr/bin/entrypoint
COPY ./frontend_storybook/storybook/app/package.json ./frontend_storybook/storybook/app/package-lock.json ./
COPY ./frontend_storybook/storybook/app/.npmrc ./
RUN npm install

COPY ./frontend_storybook/storybook/app/ ./
COPY ./frontend_bundler_vite/client/app/src/styles ./styles
COPY ./frontend_bundler_vite/client/app/src/scripts ./scripts
COPY ./frontend_static/client/app/views ./views
COPY ./frontend_static/client/app/fixtures ./fixtures
RUN npm run build

EXPOSE 6006
ENTRYPOINT ["entrypoint"]

FROM nginx:alpine AS frontend_storybook_nginx

COPY ./frontend_storybook/nginx/config /etc/config
COPY ./frontend_storybook/nginx/docker/entrypoint.sh /usr/bin/entrypoint
COPY ./frontend_bundler_vite/tommy/optimized /var/assets
COPY --from=frontend_storybook /app/dist /var/static

ENTRYPOINT entrypoint
