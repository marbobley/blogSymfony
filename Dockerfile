FROM dunglas/frankenphp:1-php8.4

WORKDIR /app

RUN install-php-extensions \
	pdo_mysql \
	gd \
	intl \
	zip \
	opcache \
    apcu

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copy sources
COPY --link . ./

RUN chmod +x bin/console

# FrankenPHP avec Symfony
ENV FRANKENPHP_CONFIG="worker /app/public/index.php"
ENV XDEBUG_MODE=off
ENV FRANKENPHP_WORKER_CONFIG=watch
ENV SERVER_NAME=localhost

EXPOSE 80 443
