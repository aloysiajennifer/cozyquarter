FROM php:8.3-fpm

# Install dependencies Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip curl git \
    && docker-php-ext-install pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set workdir to project root (sesuai volume .:/code)
WORKDIR /code
