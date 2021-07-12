FROM php:7.4-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
        acl \
        libcurl4-openssl-dev \
        procps \
        zip \
        unzip \
        wget \
        git \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \ 
        unixodbc \
        unixodbc-dev \
        ldap-utils libldap2-dev \
        libpng-dev && \
    docker-php-ext-configure pdo_odbc --with-pdo-odbc=unixODBC,/usr && \
    docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
    docker-php-ext-install \         
        gd \
        zip \
        intl \
        mysqli \
        pdo \
        pdo_odbc \
        xml \
        curl \
        mbstring \
        opcache \
        ldap

RUN docker-php-source extract \
    && set -x \
    && cd /usr/src/php/ext/odbc \
    && phpize \
    && sed -ri 's@^ *test +"\$PHP_.*" *= *"no" *&& *PHP_.*=yes *$@#&@g' configure \
    && docker-php-ext-configure odbc --with-unixODBC=shared,/usr \
    && docker-php-ext-install odbc \
    && docker-php-ext-enable odbc \
    && docker-php-source delete \
    && php -m

RUN pecl install xdebug-3.0.2 \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable xdebug    

COPY ./docker/odbc/ibm-iaccess-1.1.0.12-1.0.amd64.deb /tmp/
RUN dpkg -i /tmp/ibm-iaccess-1.1.0.12-1.0.amd64.deb
RUN rm /tmp/ibm-iaccess-1.1.0.12-1.0.amd64.deb
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.0.0
COPY ./docker/xdebug/xdebug.ini $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini
COPY ./docker/opcache/opcache.ini $PHP_INI_DIR/conf.d/docker-php-ext-opcache.ini
COPY ./docker/odbc/odbc.ini /etc/odbc.ini
WORKDIR /var/www/project