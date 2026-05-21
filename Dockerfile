# --- Étape 1 : Build des assets JS/CSS (Vite) ---
FROM node:20-alpine AS assets-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# --- Étape 2 : Image finale PHP + Nginx ---
FROM php:8.2-fpm-alpine

# Installation des dépendances système et extensions PHP nécessaires pour Laravel
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    libzip-dev \
    unzip \
    git \
    nginx \
    oniguruma-dev \
    libxml2-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip bcmath mbstring exif pcntl bcmath opcache

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copie du code source
COPY . .

# Suppression des dossiers inutiles pour la prod (si déjà présents localement)
RUN rm -rf node_modules vendor

# Installation des dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Récupération des assets compilés depuis l'étape 1
COPY --from=assets-builder /app/public/build ./public/build

# Configuration des permissions pour Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Configuration Nginx
COPY ./docker/nginx.conf /etc/nginx/http.d/default.conf

# Optimisation de la configuration PHP pour la prod
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

EXPOSE 80

# Commande de lancement (PHP-FPM en arrière-plan et Nginx au premier plan)
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
