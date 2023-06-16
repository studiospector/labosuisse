ARG CI_REGISTRY
ARG CI_PROJECT_PATH
ARG CI_COMMIT_REF_NAME

FROM ${CI_REGISTRY}/${CI_PROJECT_PATH}/${CI_COMMIT_REF_NAME}/frontend_static:latest AS frontend_static
FROM nginx:alpine

COPY ./frontend_static/nginx/config /etc/config
COPY ./frontend_static/nginx/docker/entrypoint.sh /usr/bin/entrypoint
COPY ./frontend_bundler_vite/tommy/optimized /var/assets
COPY --from=frontend_static /app/dist/bundle /var/bundle
COPY --from=frontend_static /app/dist/public /var/static

ENTRYPOINT entrypoint
