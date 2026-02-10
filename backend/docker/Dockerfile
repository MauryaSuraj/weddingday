# Backend Dockerfile
# PHP-FPM 8.3 with Laravel 12 optimized for production

FROM php:8.3-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    g++ \
    gcc \
    git \
    libpq-dev \
    postgresql-client \
    zip \
    unzip \
    curl \
    bash \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install \
    bcmath \
    ctype \
    fileinfo \
    json \
    mbstring \
    pdo \
    pdo_pgsql \
    pdo_mysql \
    tokenizer

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /app && \
    chmod -R 755 /app && \
    chmod -R 775 storage bootstrap/cache

# Initialize Supervisor
RUN mkdir -p /etc/supervisor/conf.d

COPY docker/supervisor.conf /etc/supervisor/conf.d/laravel.conf

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:9000/status || exit 1

EXPOSE 9000

CMD ["php-fpm"]
