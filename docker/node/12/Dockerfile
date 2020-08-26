FROM node:12-alpine

MAINTAINER BRACKETS by TRIAD

WORKDIR /var/www/html

ARG uid=1000
ARG gid=1000

RUN apk --no-cache add shadow \
    && usermod -u $uid -g $gid node
