FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install \
        intl \
        zip \
        mysqli \
        pdo \
        pdo_mysql \
        opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy project
COPY . .

# Set permission untuk CodeIgniter
RUN chmod -R 777 writable

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

EXPOSE 8080

CMD php -S 0.0.0.0:$PORT -t public
