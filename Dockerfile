FROM php:8.2-cli

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (including PostgreSQL for Render's database)
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first (for better Docker layer caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy package files
COPY package.json package-lock.json ./
RUN npm ci

# Copy application files
COPY . .

# Run composer scripts and build assets
RUN composer dump-autoload --optimize --no-dev
RUN npm run build

# Set permissions for storage and cache directories
RUN chmod -R 775 storage bootstrap/cache

# Expose port (Render sets PORT env var)
EXPOSE 10000

# Start PHP built-in server
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}

