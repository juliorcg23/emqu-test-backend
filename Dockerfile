FROM php:7.4-apache

RUN docker-php-ext-install pdo pdo_mysql sockets bcmath
RUN curl -sS https://getcomposer.org/installer | php -- \
     --install-dir=/usr/local/bin --filename=composer
RUN apt-get update -y && apt-get install -y zip iputils-ping
RUN a2enmod rewrite headers

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" && \
    echo "error_log = /dev/stderr" >> "$PHP_INI_DIR/php.ini"

COPY --from=composer:latest /usr/bin/composer /usr/bin/componser

WORKDIR /var/www/html
COPY . .
RUN cp .env.example .env
RUN composer install \
  && php artisan key:generate

ENTRYPOINT docker-php-entrypoint apache2-foreground
