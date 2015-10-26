#!/usr/bin/env bash

sudo apt-get install -y nginx-full php5-cli php5-curl vim

php composer.phar self-update
php composer.phar install

echo "=> Done bootstrapping <="
