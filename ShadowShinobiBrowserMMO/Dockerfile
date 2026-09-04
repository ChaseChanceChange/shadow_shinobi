FROM php:8.2-apache

# The legacy game uses the mysqli extension for its database layer.
RUN docker-php-ext-install mysqli

# Preload the compatibility boundary before legacy entry points execute.
COPY src/legacy_compat.php /var/www/legacy_compat.php
RUN printf '%s\n' 'auto_prepend_file=/var/www/legacy_compat.php' > /usr/local/etc/php/conf.d/zz-shadow-shinobi-legacy.ini

# Keep Apache's document root pointed at the legacy game source.
WORKDIR /var/www/html
