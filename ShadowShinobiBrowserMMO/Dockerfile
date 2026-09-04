FROM php:8.2-apache

# The legacy game uses the mysqli extension for its database layer.
RUN docker-php-ext-install mysqli

# Keep Apache's document root pointed at the legacy game source.
WORKDIR /var/www/html
