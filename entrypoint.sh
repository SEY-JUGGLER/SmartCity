#!/bin/sh

echo "Configuration Laravel (DEV)..."

php artisan key:generate --force

php artisan storage:link || true

# ⚠️ Supprime toutes les tables et recrée la base avant de lancer les seeders
php artisan migrate:fresh --seed --force

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

php artisan serve --host=0.0.0.0 --port=$PORT
