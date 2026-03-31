FROM php:8.2-apache

# Install mysqli extension for database connection
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy your website files from the subfolder to the container
COPY ./driptee/ /var/www/html/

# Set permissions so Apache can serve the files
RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 755 /var/www/html/