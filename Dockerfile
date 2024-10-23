# Use the official PHP image with Apache
FROM php:8.3-apache

# Enable Apache mod_rewrite for .htaccess support
RUN a2enmod rewrite

# Install PostgreSQL dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# Copy php.ini for PHP configuration
COPY ./php/php.ini /usr/local/etc/php/php.ini

# Set the working directory inside the container
WORKDIR /var/www/html

# Expose port 80 for Apache
EXPOSE 80