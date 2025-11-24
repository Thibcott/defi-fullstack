FROM php:8.2-cli-alpine

WORKDIR /var/www/html

# Dépendances système
RUN apk add --no-cache \
    bash \
    git \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    $PHPIZE_DEPS

# Extensions PHP (PDO + Postgres)
RUN docker-php-ext-configure intl \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        intl \
        zip \
    && docker-php-ext-enable opcache

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier le code Symfony
COPY . .

# Installer les dépendances Symfony
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Droits sur var/
RUN mkdir -p var \
    && chown -R www-data:www-data var \
    && chmod -R 775 var

# Exposer le port 8000 pour le serveur intégré
EXPOSE 8000

# Lancer le serveur PHP intégré (Symfony)
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
