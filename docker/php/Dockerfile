# Upgrade from the old Buster image to a current one
FROM php:8.4-fpm

# Set working directory
WORKDIR /var/www/crm/app

# Install composer
COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

# Install system dependencies
# Bookworm (the new Debian) handles these packages much better
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libpng-dev \
    nano && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install intl pdo_mysql zip bcmath gd

COPY php.ini /usr/local/etc/php/

# Set permissions
RUN chown -R www-data:www-data .
