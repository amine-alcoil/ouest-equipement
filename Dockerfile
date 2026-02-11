# Stage 1: Build application
FROM php:8.2-cli

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


# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (needed for Vite build)
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash && \ apt-get update && pt-get install -y nodejs

WORKDIR /var/www/html

# Copy project files
COPY . .

# Expose port (Railway provides $PORT)
EXPOSE 8000

# Install PHP dependencies
# We use --no-dev for production and ensure scripts are disabled to avoid npm conflicts here
RUN composer install

# Install Node dependencies and build assets
RUN npm install 
RUN npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache



# Improved start command with caching for performance
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000