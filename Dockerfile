# Stage 1: Build Laravel Backend with Composer
FROM php:8.1-fpm as builder

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer.json and composer.lock file
COPY composer.json composer.lock ./

# Install composer dependencies
RUN composer install --prefer-dist --no-scripts --no-dev --no-interaction

# Copy the application code to the container
COPY . .

# Copy .env.example to .env and generate key
RUN cp .env.example .env
RUN php artisan key:generate

# Stage 2: Build Vue.js Frontend with Node.js
FROM node:14 as frontend

WORKDIR /app

# Copy package.json and package-lock.json
COPY package.json package-lock.json ./

# Install npm dependencies
RUN npm install

# Copy the application code to the container
COPY . .

# Build Vue.js
RUN npm run production

# Stage 3: Setup Nginx and PHP-FPM for serving application
FROM nginx:1.19-alpine

# Copy PHP-FPM config and Nginx config
COPY .docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf
COPY --from=builder /usr/local/etc/php /usr/local/etc/php

# Copy application code and assets from builder and frontend stages
COPY --from=builder /app /var/www/html
COPY --from=frontend /app/public/js /var/www/html/public/js
COPY --from=frontend /app/public/css /var/www/html/public/css

# Change the working directory to the application's directory
WORKDIR /var/www/html

# Give the www-data user ownership of the application directory
RUN chown -R www-data:www-data .

# Expose port 80 for the application
EXPOSE 80

# Start Nginx and keep it running
CMD ["nginx", "-g", "daemon off;"]
