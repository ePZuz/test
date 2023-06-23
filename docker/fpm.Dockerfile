FROM php:8.1-fpm

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
            git wget autoconf pkg-config \
            ghostscript \
            libmcrypt-dev \
            zlib1g-dev zip \
            libcurl4-gnutls-dev \
            libssl-dev \
            libzip-dev \
    && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install bcmath pcntl curl pdo_mysql zip sockets

WORKDIR /var/www/html
RUN wget -q https://getcomposer.org/download/latest-2.x/composer.phar -O composer.phar
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY composer.json .
COPY composer.lock .
RUN php composer.phar install --no-dev --prefer-dist --no-scripts --no-autoloader

RUN echo "memory_limit=-1;" > /usr/local/etc/php/conf.d/php.ini
RUN echo "upload_max_filesize=100M;" >> /usr/local/etc/php/conf.d/php.ini
RUN echo "post_max_size=100M;" >> /usr/local/etc/php/conf.d/php.ini

COPY . /var/www/html

RUN php composer.phar dump-autoload

