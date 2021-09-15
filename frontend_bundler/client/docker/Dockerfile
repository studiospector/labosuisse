FROM node:12-alpine
WORKDIR /app
RUN apk add --no-cache git --update

# Install dependencies
COPY ./frontend_bundler/client/app/package.json ./
COPY ./frontend_bundler/client/app/package-lock.json ./
RUN npm install

COPY ./frontend_bundler/client/docker/entrypoint.sh /usr/bin/entrypoint
COPY ./frontend_bundler/client/app/ ./

ENV NODE_ENV=production
RUN npm run build

ENTRYPOINT ["entrypoint"]
