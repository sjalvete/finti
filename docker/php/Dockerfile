FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git zip unzip \
    libzip-dev libicu-dev libonig-dev libxml2-dev \
    libjpeg-dev libpng-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql intl gd zip bcmath opcache exif \
    && rm -rf /var/lib/apt/lists/*

# (Optional but common for Laravel tooling/queues)
RUN docker-php-ext-install pcntl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dev-friendly opcache
RUN { \
  echo 'opcache.enable=1'; \
  echo 'opcache.enable_cli=1'; \
  echo 'opcache.validate_timestamps=1'; \
  echo 'opcache.revalidate_freq=0'; \
} > /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html
