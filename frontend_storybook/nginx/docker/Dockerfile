ARG CI_COMMIT_REF_NAME
ARG CI_REGISTRY_IMAGE
FROM ${CI_REGISTRY_IMAGE}/${CI_COMMIT_REF_NAME}/frontend_storybook:latest AS frontend_storybook
FROM nginx:alpine

COPY ./frontend_storybook/nginx/config /etc/config
COPY ./frontend_storybook/nginx/docker/entrypoint.sh /usr/bin/entrypoint
COPY ./frontend_bundler/tommy/optimized /var/assets
COPY --from=frontend_storybook /app/dist/public /var/static

ENTRYPOINT entrypoint
