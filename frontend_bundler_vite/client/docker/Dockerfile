FROM node:18.16.0-alpine
WORKDIR /app
RUN apk add --no-cache git --update

# Install dependencies
COPY ./frontend_bundler_vite/client/app/package.json ./
COPY ./frontend_bundler_vite/client/app/package-lock.json ./
COPY ./frontend_bundler_vite/client/app/.npmrc ./
RUN npm install

COPY ./frontend_bundler_vite/client/docker/entrypoint.sh /usr/bin/entrypoint
COPY ./frontend_bundler_vite/client/app/ ./

ENV NODE_ENV=production
RUN npm run build

ENTRYPOINT ["entrypoint"]
