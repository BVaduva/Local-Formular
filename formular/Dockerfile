FROM php:8.3-apache

ARG DEBIAN_FRONTEND=noninteractive
RUN pecl channel-update pecl.php.net && apt-get update && pecl install apcu && docker-php-ext-enable apcu
RUN apt-get install -y unzip zip git mcrypt

# Install the mysqli extension for PHP
RUN docker-php-ext-install mysqli

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Optionally, set the working directory (if needed)
# WORKDIR /var/www/html