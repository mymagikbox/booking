FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg-dev \
    	libpq-dev \
        libpng-dev \
        zlib1g-dev \
        libzip-dev \
        graphviz \
        imagemagick \
        libmagickwand-dev \
        libmagickcore-dev \
        ghostscript \
        ffmpeg \
    	libicu-dev \
    && docker-php-ext-install intl \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql pdo_pgsql zip calendar pcntl

RUN pecl install imagick-3.7.0 \
    && docker-php-ext-enable imagick

COPY ./composer.phar /usr/local/bin/composer

RUN mkdir /app

RUN ln -fs /usr/share/zoneinfo/Europe/Moscow /etc/localtime && dpkg-reconfigure -f noninteractive tzdata

# Enable and configure xdebug
RUN pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug
