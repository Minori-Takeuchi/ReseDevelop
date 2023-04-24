# frontend Dockerfile
FROM node:16.13.1-alpine

RUN apk update && \
    apk add --no-cache yarn

WORKDIR /usr/src/app

COPY package*.json ./

RUN yarn install

ADD docker-entrypoint.sh ./
RUN chmod +x ./docker-entrypoint.sh

ENV LANG=C.UTF-8
ENV TZ=Asia/Tokyo
