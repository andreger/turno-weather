FROM php:8.2-fpm
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    && docker-php-ext-install pdo_mysql \
    && pecl install xdebug-3.2.1 \
	&& docker-php-ext-enable xdebug

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the existing application
COPY . .

RUN php artisan key:generate

# Expose the port for Laravel
EXPOSE 9000

# Start the PHP-FPM server
CMD ["php-fpm"]
