FROM node:12-alpine AS modules
WORKDIR /app

RUN apk add --no-cache git --update
COPY ./frontend_storybook/storybook/docker/entrypoint.sh /usr/bin/entrypoint
COPY ./frontend_storybook/storybook/app/package.json ./frontend_storybook/storybook/app/package-lock.json ./
COPY ./frontend_storybook/storybook/app/.npmrc ./
RUN npm install

COPY ./frontend_storybook/storybook/app/ ./
COPY ./frontend_bundler/client/app/styles ./styles
COPY ./frontend_bundler/client/app/scripts ./scripts
COPY ./frontend_static/client/app/views ./views
COPY ./frontend_static/client/app/fixtures ./fixtures
RUN npm run build

EXPOSE 6006
ENTRYPOINT ["entrypoint"]
