FROM php:8.2-cli

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install unzip utility and libs needed by zip PHP extension 
RUN apt-get update && apt-get install -y zlib1g-dev libzip-dev unzip libpq-dev
RUN docker-php-ext-install zip && docker-php-ext-install pcntl
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
RUN docker-php-ext-install sockets

RUN pecl install xdebug-3.2.1 && docker-php-ext-enable xdebug

#RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pdo pdo_pgsql pgsql

#RUN pecl install redis-5.3.7 \
#	&& pecl install xdebug-3.2.1 \
#	&& docker-php-ext-enable redis xdebug


COPY ./php.ini /usr/local/etc/php/conf.d/php-custom.ini