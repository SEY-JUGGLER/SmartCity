#!/bin/sh

echo "Configuration Laravel..."

php artisan key:generate --force

php artisan storage:link || true

php artisan migrate --force

php artisan config:cache

php artisan route:cache

php artisan view:cache

php artisan optimize

php artisan serve \
    --host=0.0.0.0 \
    --port=$PORT