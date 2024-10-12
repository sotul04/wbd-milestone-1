# Gunakan image PHP dengan Apache
FROM php:8.3-apache

# Enable Apache mod_rewrite untuk dukungan .htaccess
RUN a2enmod rewrite

# Install dependencies untuk PostgreSQL
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# Copy php.ini untuk konfigurasi PHP
COPY ./php/php.ini /usr/local/etc/php/php.ini

# Set working directory di dalam container
WORKDIR /var/www/html

# Expose port 80 untuk Apache
EXPOSE 80
