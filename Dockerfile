FROM dunglas/frankenphp

COPY . /app

RUN apt-get update && apt-get install -y git libicu-dev zip unzip

RUN apt-get install sqlite3 libsqlite3-dev

RUN docker-php-ext-install intl pdo_sqlite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \

RUN composer install