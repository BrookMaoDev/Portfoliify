FROM php:8.2.12-apache
RUN docker-php-ext-install pdo_mysql
COPY src/ /var/www/html/
EXPOSE 80
