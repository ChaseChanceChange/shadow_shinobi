FROM php:8.2-apache

# The legacy game uses the mysqli extension for its database layer.
RUN docker-php-ext-install mysqli

# Preload the Shadow player-language boundary before legacy entry points execute.
# The boundary itself includes legacy_compat.php, i18n.php, and the player-output cleanup.
COPY src/legacy_compat.php /var/www/legacy_compat.php
RUN printf '%s\n' 'auto_prepend_file=/var/www/html/player_language.php' > /usr/local/etc/php/conf.d/zz-shadow-shinobi-legacy.ini

# Keep Apache's document root pointed at the legacy game source.
WORKDIR /var/www/html
