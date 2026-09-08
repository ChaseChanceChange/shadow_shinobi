FROM php:8.2-apache

# The legacy game uses the mysqli extension for its database layer.
RUN docker-php-ext-install mysqli

# Preload the ACTIVE Shadow Shinobi player-language/compatibility boundary.
# The source tree is bind-mounted at runtime, so the same path is used both
# during image builds and inside the running development container.
RUN printf '%s\n' 'auto_prepend_file=/var/www/html/player_language.php' > /usr/local/etc/php/conf.d/zz-shadow-shinobi-legacy.ini

# Keep Apache's document root pointed at the active legacy game source.
WORKDIR /var/www/html
