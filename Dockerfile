FROM php:8.2-cli

# 1. Install sistem dependencies (gd, zip, pdo_mysql)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install gd zip pdo_mysql

# 2. Set direktori kerja
WORKDIR /app

# 3. Copy semua file proyek
COPY . .

# 4. Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# 5. Atur permission folder Laravel
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# 6. Jalankan server bawaan Laravel pada Port yang diberikan oleh Railway
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}