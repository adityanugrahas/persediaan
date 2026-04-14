# Use official PHP FPM Alpine image for a lightweight container
FROM php:8.2-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    sqlite-dev \
    icu-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_sqlite \
        gd \
        zip \
        intl \
        mbstring \
        bcmath

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Create logs directory and set permissions
RUN mkdir -p /var/www/html/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/img \
    && chmod -R 775 /var/www/html/logs \
    && chmod 664 /var/www/html/database.sqlite

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
