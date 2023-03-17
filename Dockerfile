FROM php:8.1.3-fpm
RUN usermod -u 1000 www-data


WORKDIR /var/www/html
ADD ./ /var/www/html

RUN link /usr/local/bin/php /usr/bin/php

# Install MySQL PDO
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_mysql

USER www-data:www-data
