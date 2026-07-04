FROM php:8.2-apache

# 1. Hapus semua konfigurasi MPM yang ada
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-available/mpm_*.load

# 2. Masukkan file konfigurasi MPM kita yang bersih
COPY apache-mpm.conf /etc/apache2/mods-enabled/mpm_prefork.load

# 3. Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install gd zip pdo_mysql

WORKDIR /var/www/html
COPY . .

# 4. Setup Apache untuk Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# 5. Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache