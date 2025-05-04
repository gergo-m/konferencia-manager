FROM php:8-apache

# Install system dependencies first
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zlib1g-dev \
    && docker-php-ext-install zip mysqli pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy your application files
COPY . /var/www/html/

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Make sure Apache can write to necessary directories
RUN chown -R www-data:www-data /var/www/html

# Create a directory for the CA certificate and set permissions
RUN mkdir -p /var/www/ssl && \
    chown -R www-data:www-data /var/www/ssl

# Copy the CA certificate from Render's secret mount point to your app's SSL directory
# Copy the entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set the entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
