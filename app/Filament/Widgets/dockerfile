# =====================
# STAGE 1 : FRONTEND
# =====================
FROM node:22 AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run build


# =====================
# STAGE 2 : BACKEND
# =====================
FROM php:8.3-fpm

# Dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    zip \
    gd

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copier les fichiers
COPY . .

# Copier les assets compilés
COPY --from=frontend /app/public/build ./public/build

# Installer les dépendances PHP
RUN composer install \
    --no-dev \
    --optimize-autoloader

# Permissions
RUN chmod -R 775 storage bootstrap/cache

# Script de démarrage
COPY entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

EXPOSE 10000

CMD ["/entrypoint.sh"]