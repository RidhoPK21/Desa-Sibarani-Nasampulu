# Menggunakan PHP versi 8.4 (Sesuai dengan spesifikasi laptop/Laravel Anda)
FROM php:8.4-cli

# Menginstal alat bantu, driver PostgreSQL, dependensi GD, dan dependensi lainnya
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_pgsql gd \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Menentukan lokasi kerja di dalam container
WORKDIR /app
