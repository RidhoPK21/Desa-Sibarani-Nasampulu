#!/bin/sh
set -e

# Cek apakah folder vendor ada
if [ ! -d "vendor" ]; then
    echo "Folder vendor tidak ditemukan. Menjalankan composer install..."
    composer install --no-interaction --optimize-autoloader || composer install --no-interaction --optimize-autoloader --ignore-platform-reqs
fi

# Cek apakah file .env ada
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

# Generate key jika kosong
if ! grep -q "APP_KEY=base64" .env || [ -z "$(grep "APP_KEY=" .env | cut -d '=' -f2)" ]; then
    php artisan key:generate --force
fi

# Bersihkan cache rute dan config agar perubahan terdeteksi
php artisan config:clear
php artisan route:clear

# Jalankan migrasi
php artisan migrate --force

# Jalankan seeder
php artisan db:seed --force || echo "Seeding terlewati."

exec "$@"
