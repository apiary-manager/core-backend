#!/usr/bin/env bash

# sh docker-frontend-update.sh

echo "
cd ControlPasec-Laravel/frontend
git pull
yarn
yarn build
" | docker exec -i Laravel-php-fpm bash