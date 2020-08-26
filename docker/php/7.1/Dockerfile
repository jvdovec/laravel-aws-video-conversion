FROM ubuntu:16.04

MAINTAINER BRACKETS by TRIAD

RUN groupadd -g 1000 harbor
RUN useradd -ms /bin/bash -u 1000 -g harbor harbor

RUN apt-get update \
    && apt-get install -y locales \
    && locale-gen en_US.UTF-8

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

RUN apt-get update \
    && apt-get install -y curl zip unzip git software-properties-common gnupg gosu supervisor \
    && add-apt-repository -y ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y php7.1-fpm php7.1-cli php7.1-mcrypt php7.1-gd \
       php7.1-pgsql php7.1-imap php-memcached php7.1-mbstring php7.1-xml php7.1-curl \
       php7.1-imagick php7.1-zip php7.1-bcmath php7.1-xdebug php7.1-soap php7.1-redis \
       php7.1-bcmath \
    && gosu nobody true \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && mkdir /run/php \
    && gosu harbor composer global require "laravel/installer" \
    && gosu harbor composer global require "brackets/craftable-installer" \
    && apt-get remove -y --purge software-properties-common \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ENV PATH "$PATH:/root/.composer/vendor/bin"

COPY php-fpm.conf /etc/php/7.1/fpm/php-fpm.conf
COPY www.conf /etc/php/7.1/fpm/pool.d/www.conf
COPY xdebug.ini /etc/php/7.1/mods-available/xdebug.ini

RUN mkdir /root/.config \
    && chown -R root:root /root/.config

RUN mkdir /home/harbor/.config \
    && chown -R harbor /home/harbor/.config

EXPOSE 9000

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

WORKDIR /var/www/html

ENTRYPOINT ["docker-entrypoint.sh"]
