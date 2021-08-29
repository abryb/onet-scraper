FROM php:8.0-cli-alpine

RUN docker-php-ext-install pcntl

WORKDIR /usr/src/app

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

CMD ["bin/run.php"]
