#!/bin/bash
set -e

echo "=== Campus Manager — Démarrage ==="
echo "PORT: ${PORT:-8080}"

# Créer le fichier SQLite
mkdir -p database
touch database/database.sqlite

# Permissions
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Vider les caches compilés (évite les conflits)
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes*.php

# Migrations
echo "[INFO] Migrations..."
php artisan migrate --force
echo "[OK] Migrations OK"

# Serveur
echo "[INFO] Démarrage serveur sur 0.0.0.0:${PORT:-8080}"
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
