FROM php:7.4-apache

RUN docker-php-ext-install pdo pdo_mysql sockets
RUN curl -sS https://getcomposer.org/installer | php -- \
     --install-dir=/usr/local/bin --filename=composer
RUN apt-get update -y
RUN apt-get install -y default-mysql-server

COPY --from=composer:latest /usr/bin/composer /usr/bin/componser}

# Create DB, TestDB and user
ARG DB_NAME=emqu_db
ARG DB_USER=emqu_user
ARG DB_PASS=password
RUN service mysql start && \
  mysql -e "CREATE DATABASE $DB_NAME CHARACTER SET = 'utf8' COLLATE = 'utf8_unicode_ci';" && \
  mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* to $DB_USER@'localhost' IDENTIFIED BY '$DB_PASS';" && \
  mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* to $DB_USER@'%' IDENTIFIED BY '$DB_PASS';"

WORKDIR /emqu-backend
COPY . .
RUN cp .env.example .env
RUN composer install
RUN php artisan migrate --seed
RUN php artisan key:generate

ENTRYPOINT service mysqld start \
  php artisan serve
