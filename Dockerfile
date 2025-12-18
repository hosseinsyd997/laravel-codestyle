FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    sqlite3 \
    libsqlite3-dev \
    cron \
    && docker-php-ext-install pdo pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY ./backend /var/www

RUN touch /var/log/cron.log && chmod 0666 /var/log/cron.log

RUN echo "* * * * * cd /var/www && php artisan schedule:run >> /var/log/cron.log 2>&1" > /etc/cron.d/laravel-cron \
    && chmod 0644 /etc/cron.d/laravel-cron \
    && crontab /etc/cron.d/laravel-cron

CMD ["sh", "-c", "cron && php-fpm"]
