#!/usr/bin/env bash

# sh docker-supervisor-update.sh

echo "
yes|/bin/cp -R -f  /ControlPasec-Laravel/docker-files/php-fpm/supervisor-apps/* /etc/supervisor/conf.d
supervisorctl update
supervisorctl restart horizon:*
" | docker exec -i Laravel-php-fpm bash
