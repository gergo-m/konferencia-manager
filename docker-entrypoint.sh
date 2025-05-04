#!/bin/sh
set -e

# Copy the CA certificate from Render's secret location to your app's directory
cp /etc/secrets/ca.pem /var/www/ssl/ca.pem

# Set proper permissions for the CA certificate
chmod 644 /var/www/ssl/ca.pem
chown www-data:www-data /var/www/ssl/ca.pem

# Start Apache
exec apache2-foreground
