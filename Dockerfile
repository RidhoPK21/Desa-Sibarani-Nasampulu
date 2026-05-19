# Menggunakan PHP versi 8.4 (Sesuai dengan spesifikasi laptop/Laravel Anda)
FROM php:8.4-cli

# Menginstal alat bantu dan driver PostgreSQL + GD extension
RUN apt-get update && apt-get install -y libpq-dev zip unzip \
    libfreetype-dev libjpeg-dev libpng-dev zlib1g-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Menentukan lokasi kerja di dalam container
WORKDIR /app