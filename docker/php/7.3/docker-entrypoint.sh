#!/usr/bin/env bash

# Run PHP-FPM as current user
if [[ ! -z "$HARBOR_USER_UID" ]]; then
    sed -i "s/user\ \=.*/user\ \= $HARBOR_USER_UID/g" /etc/php/7.3/fpm/pool.d/www.conf

    # Set UID and GID of user "harbor"
    usermod -u ${HARBOR_USER_UID} harbor
fi

if [[ ! "production" == "$APP_ENV" ]] && [[ ! "prod" == "$APP_ENV" ]] && [[ ! "testing" == "$APP_ENV" ]] && [[ "on" == "$DOCKER_PHP_XDEBUG" ]]; then
    # Enable xdebug

    ## FPM
    ln -sf /etc/php/7.3/mods-available/xdebug.ini /etc/php/7.3/fpm/conf.d/20-xdebug.ini

    ## CLI
    ln -sf /etc/php/7.3/mods-available/xdebug.ini /etc/php/7.3/cli/conf.d/20-xdebug.ini
else
    # Disable xdebug

    ## FPM
    if [[ -e /etc/php/7.3/fpm/conf.d/20-xdebug.ini ]]; then
        rm -f /etc/php/7.3/fpm/conf.d/20-xdebug.ini
    fi

    ## CLI
    if [[ -e /etc/php/7.3/cli/conf.d/20-xdebug.ini ]]; then
        rm -f /etc/php/7.3/cli/conf.d/20-xdebug.ini
    fi
fi

# Config /etc/php/7.3/mods-available/xdebug.ini
sed -i "s/xdebug\.remote_host\=.*/xdebug\.remote_host\=$XDEBUG_HOST/g" /etc/php/7.3/mods-available/xdebug.ini

if [[ -f /root/.ssh/id_rsa ]]; then
    chmod -R 0600 /root/.ssh/id_rsa
fi

if [[ -f /home/harbor/.ssh/id_rsa ]]; then
    chmod -R 0600 /home/harbor/.ssh/id_rsa
fi

PATH=$PATH:/home/harbor/.composer/vendor/bin
export PATH

if [[ $# -gt 0 ]];then
    # If we passed a command, run it as current user
    exec gosu ${HARBOR_USER_UID} "$@"
else
    # Otherwise start supervisord
    /usr/bin/supervisord
fi
