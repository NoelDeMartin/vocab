FROM php:8.1-fpm-alpine

WORKDIR /app

# Install PHP dependencies
RUN set -x \
    && apk update \
    && apk add zlib-dev libzip-dev \
    && docker-php-ext-install zip bcmath \
    && docker-php-ext-enable bcmath \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && composer self-update --1

# Install Supervisor
RUN apk add --no-cache supervisor

COPY docker/supervisord.conf /etc/supervisord.conf

# Copy files
COPY . /app

# Install Composer dependencies
RUN composer install --optimize-autoloader --no-dev

# Start Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
