FROM php:8.3.4-fpm-alpine3.19

RUN apk update --no-cache && \
    apk upgrade --no-cache
RUN apk add --no-cache \
        supervisor
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    linux-headers
RUN apk add --no-cache \
    freetype-dev \
    jpeg-dev \
    icu-dev \
    libzip-dev

#RUN apk add --no-cache \
#    chromium \
#    chromium-chromedriver \
#    unzip

#ENV PANTHER_NO_SANDBOX=1 \
#    PANTHER_CHROME_ARGUMENTS='--disable-dev-shm-usage --no-sandbox' \
#    PANTHER_CHROME_BINARY=/usr/bin/chromium-browser

 RUN set -ex \
 && apk --no-cache add \
 postgresql-dev

RUN apk add --no-cache \
    chromium \
    chromium-chromedriver \
    unzip

ENV PANTHER_NO_SANDBOX=1 \
    PANTHER_CHROME_ARGUMENTS='--disable-dev-shm-usage --no-sandbox' \
    PANTHER_CHROME_BINARY=/usr/bin/chromium-browser

#####################################
# PHP Extensions
#####################################
# Install the PHP shared memory driver
RUN pecl install APCu && \
    docker-php-ext-enable apcu

# Install the PHP bcmath extension
RUN docker-php-ext-install bcmath

# Install for image manipulation
RUN docker-php-ext-install exif

# Install the PHP graphics library
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg
RUN docker-php-ext-install gd

# Install the PHP intl extention
RUN docker-php-ext-install intl

# Install the PHP mysqli extention
RUN docker-php-ext-install mysqli && \
    docker-php-ext-enable mysqli

# Install the PHP opcache extention
RUN docker-php-ext-enable opcache

# Install the PHP pcntl extention
RUN docker-php-ext-install pcntl

# Install the PHP pdo_mysql pdo_pgsql extention
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql

# Install the PHP redis driver
RUN pecl install redis && \
    docker-php-ext-enable redis

# install XDebug but without enabling
RUN pecl install xdebug

# Install the PHP zip extention
RUN docker-php-ext-install zip

#####################################
# Composer
#####################################
RUN curl -s http://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer


# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

#RUN composer install

#####################################
# Cleanup
#####################################
RUN apk del --no-network .build-deps
RUN rm -rf /tmp/* /var/tmp/*


# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
