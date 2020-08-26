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
    && apt-get install -y php7.2-fpm php7.2-cli php7.2-gd \
       php7.2-pgsql php7.2-imap php-memcached php7.2-mbstring php7.2-xml php7.2-curl \
       php7.2-imagick php7.2-zip php7.2-bcmath php7.2-xdebug php7.2-soap php7.2-redis \
       php7.2-bcmath \
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

COPY php-fpm.conf /etc/php/7.2/fpm/php-fpm.conf
COPY www.conf /etc/php/7.2/fpm/pool.d/www.conf
COPY xdebug.ini /etc/php/7.2/mods-available/xdebug.ini

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
