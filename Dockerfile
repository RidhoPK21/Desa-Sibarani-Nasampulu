# Menggunakan PHP versi 8.4 (Sesuai dengan spesifikasi laptop/Laravel Anda)
FROM php:8.4-cli

# Menginstal alat bantu dan driver PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev zip unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Menentukan lokasi kerja di dalam container
WORKDIR /app