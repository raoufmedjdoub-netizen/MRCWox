FROM php:8.1-fpm

# Arguments
ARG user=www-data
ARG uid=1000

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libicu-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    ffmpeg \
    fonts-liberation \
    libxrender1 \
    libxext6 \
    libfontconfig1 \
    xfonts-base \
    xfonts-75dpi \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Java JRE (required for GPSWOX tracker server)
RUN apt-get update && apt-get install -y --no-install-recommends \
    default-jre-headless \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js 18
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache \
        intl \
        sockets

# Install Redis PHP extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Créer les dossiers nécessaires
RUN mkdir -p /var/www/html/bootstrap/cache \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs

# Install PHP dependencies (sans scripts artisan - le .env n'est pas dispo au build)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Install Node socket dependencies
RUN npm install --prefix socket

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Setup GPSWOX tracker server
RUN mkdir -p /opt/traccar/conf /opt/traccar/logs /opt/traccar/web /opt/traccar/bin
COPY docker/traccar/tracker-server.jar /opt/traccar/tracker-server.jar

# Copy configs
COPY docker/nginx/default.conf /etc/nginx/sites-available/default
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

# Entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80 9001 12050

ENTRYPOINT ["/entrypoint.sh"]
