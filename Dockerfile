# Gunakan image PHP 8.2 yang sudah lengkap dengan ekstensi
FROM php:8.2-apache

# Nonaktifkan MPM default yang mungkin bentrok, lalu aktifkan satu saja (mpm_prefork)
RUN a2dismod mpm_event mpm_worker && a2enmod mpm_prefork

# Install sistem dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install gd zip pdo_mysql
# Set folder kerja
WORKDIR /var/www/html

# Copy semua file proyek Anda ke dalam container
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Jalankan instalasi tanpa mengecek platform requirements
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Atur permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Atur Apache agar mengarah ke folder public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite