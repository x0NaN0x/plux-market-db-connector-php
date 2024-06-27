# Use the official PHP 8.1 image as base
FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www/html

# Install required system packages and PHP extensions
RUN apt-get update && apt-get install -y \
  git \
  zip \
  unzip \
  libpng-dev \
  libjpeg-dev \
  libfreetype6-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install gd pdo pdo_mysql

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install application dependencies
RUN composer install

# Expose port 8080 to the Docker host
EXPOSE 8080

# Command to start the PHP built-in server
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
