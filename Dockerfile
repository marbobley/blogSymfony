FROM dunglas/frankenphp:1-php8.4 AS server_base

WORKDIR /app

VOLUME /app/var/

# persistent / runtime deps
# hadolint ignore=DL3008
RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends \
		file \
		git \
	; \
	\
	mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"; \
	\
	install-php-extensions \
		@composer \
		apcu \
		intl \
		opcache \
		zip \
        pdo_mysql \
        mysqli \
        gd \
    	;

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=dev
ENV XDEBUG_MODE=off
ENV FRANKENPHP_WORKER_CONFIG=watch


RUN set -eux; \
	install-php-extensions \
		xdebug \
	; \
	\
	{ \
		echo 'opcache.validate_timestamps=1'; \
		echo 'opcache.revalidate_freq=0'; \
		echo 'memory_limit=512M'; \
	} > "$PHP_INI_DIR/conf.d/dev.ini";


CMD [ "frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile", "--watch" ]
