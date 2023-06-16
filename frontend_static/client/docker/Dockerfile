ARG CI_REGISTRY
ARG CI_PROJECT_PATH
ARG CI_COMMIT_REF_NAME

FROM ${CI_REGISTRY}/${CI_PROJECT_PATH}/${CI_COMMIT_REF_NAME}/frontend_bundler_vite:latest AS frontend_bundler_vite

FROM node:18.16.0-alpine AS modules
WORKDIR /app

RUN apk add --no-cache git --update
COPY ./frontend_static/client/docker/entrypoint.sh /usr/bin/entrypoint
COPY ./frontend_static/client/app/package.json ./frontend_static/client/app/package-lock.json ./
RUN npm install

COPY ./frontend_static/client/app/ ./
COPY --from=frontend_bundler_vite /app/dist ./dist/bundle

ENV NODE_ENV=production
RUN npm run build

ENTRYPOINT ["entrypoint"]
