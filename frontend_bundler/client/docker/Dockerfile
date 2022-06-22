FROM node:12-alpine
WORKDIR /app
RUN apk add --no-cache git --update

# Install dependencies
COPY ./frontend_bundler/client/app/package.json ./
COPY ./frontend_bundler/client/app/package-lock.json ./
COPY ./frontend_bundler/client/app/.npmrc ./
RUN npm install

COPY ./frontend_bundler/client/docker/entrypoint.sh /usr/bin/entrypoint
COPY ./frontend_bundler/client/app/ ./

ARG LB_API_TOKEN
ARG LB_API_URL

ENV NODE_ENV=production
ENV LB_API_TOKEN=$LB_API_TOKEN
ENV LB_API_URL=$LB_API_URL

RUN npm run build

ENTRYPOINT ["entrypoint"]
