# Use the official PHP 8.2.12 image with Apache
FROM php:8.2.12-apache

# Install pdo_mysql extension
RUN docker-php-ext-install pdo pdo_mysql

# Copy the current directory content into the container
COPY . /var/www/html/

# Expose port 80 for Apache
EXPOSE 80
