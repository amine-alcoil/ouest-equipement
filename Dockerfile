# Stage 1: Build application
FROM php:8.2-cli as builder

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
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo_mysql zip bcmath

# Install Node.js (needed for Vite build)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --ignore-platform-reqs

# Install Node dependencies and build assets
RUN npm install && npm run build

# Stage 2: Production with FrankenPHP
FROM dunglas/frankenphp:1-php8.2

WORKDIR /var/www/html

# Install GD and other extensions in the final image
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libxpm-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo_mysql zip bcmath

# Copy application from builder
COPY --from=builder /var/www/html /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Railway expects the app on port 80 (or $PORT)
ENV PORT=80
EXPOSE 80

# Command to run migrations and start FrankenPHP
CMD php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && ./artisan octane:install --server=frankenphp && php artisan octane:start --server=frankenphp --host=0.0.0.0 --port=$PORT