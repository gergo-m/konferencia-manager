# Use official PHP image with Apache
FROM php:8.2-apache

# Install mysqli extension for MySQL support
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy your PHP app to the Apache web root
COPY . /var/www/html/

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
