FROM php:8.1-apache

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        zip \
        unzip \
        libzip-dev \
        nano \
    && apt-get clean \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo_mysql zip \
    && apt-get install -y locales && localedef -i pt_BR -c -f UTF-8 -A /usr/share/locale/locale.alias pt_BR.UTF-8

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./ /var/www/html/

RUN composer install

RUN a2enmod rewrite
RUN a2enmod headers

RUN mv /var/www/html/docker-init.sh / \
    && chmod +x /docker-init.sh

RUN chown -R www-data:www-data /var/www/html/

CMD ["/docker-init.sh"]