FROM php:8.2-apache

# Install PostgreSQL dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers

# Configure Apache DocumentRoot to project root
RUN sed -i 's|/var/www/html|/var/www/html|g' /etc/apache2/sites-available/000-default.conf

# Copy custom Apache config
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Copy PHP config
COPY php.ini /usr/local/etc/php/conf.d/agricart.ini

# Copy project files
COPY . /var/www/html/

# Remove local-only files from the image
RUN rm -rf /var/www/html/php \
           /var/www/html/start.bat \
           /var/www/html/.vscode \
           /var/www/html/.git \
           /var/www/html/.env.example

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod 777 /var/www/html/images

# Expose port 80
EXPOSE 80

CMD ["apache2-foreground"]
