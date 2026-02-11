# Stage 1: Build assets with Node.js
FROM node:20 as node-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: PHP Application
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libxpm-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo_mysql zip bcmath

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .
COPY --from=node-builder /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-scripts --no-interaction --ignore-platform-reqs

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port (Railway provides $PORT)
EXPOSE 80

# Command to run migrations and start the server
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-80}