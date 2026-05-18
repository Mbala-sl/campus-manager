FROM php:8.4-cli

# Dépendances système
RUN apt-get update && apt-get install -y \
    git curl unzip libzip-dev libpng-dev libonig-dev \
    libxml2-dev libsqlite3-dev sqlite3 \
    && docker-php-ext-install \
        pdo pdo_sqlite zip mbstring xml bcmath tokenizer ctype \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copier le projet
COPY . .

# Build PHP
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --no-dev --optimize-autoloader --no-interaction

# Build assets
RUN npm ci && npm run build

# Permissions
RUN chmod -R 775 storage bootstrap/cache \
    && mkdir -p database \
    && touch database/database.sqlite

EXPOSE 8080

CMD ["bash", "start.sh"]
