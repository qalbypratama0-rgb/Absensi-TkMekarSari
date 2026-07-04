FROM php:8.2-apache

# 1. Hapus konfigurasi MPM bawaan agar tidak ada konflik
RUN rm /etc/apache2/mods-enabled/mpm_*.load

# 2. Aktifkan hanya mpm_prefork
RUN a2enmod mpm_prefork rewrite

# 3. Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install gd zip pdo_mysql

WORKDIR /var/www/html
COPY . .

# 4. Copy konfigurasi Apache khusus untuk Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# 5. Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache