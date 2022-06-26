FROM php:7.4-apache

RUN apt-get update\
    && apt-get install -y git unzip cron\
    && pecl install xdebug\
    && docker-php-ext-enable xdebug\
    && docker-php-ext-install pdo_mysql\
    && docker-php-ext-install mysqli\
    && a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer  /usr/local/bin/
COPY                        ./000-default.conf /etc/apache2/sites-available/
COPY                        ./php.ini          /usr/local/etc/php/
COPY                        ./startup.sh       /

CMD /startup.sh
