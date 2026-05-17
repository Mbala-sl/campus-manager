#!/bin/bash
set -e

echo "╔══════════════════════════════════════╗"
echo "║   Campus Manager — Démarrage         ║"
echo "╚══════════════════════════════════════╝"

# ── Créer le fichier SQLite si absent ──
mkdir -p database
touch database/database.sqlite
echo "[OK] Base de données SQLite prête"

# ── Permissions ──
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# ── Migrations ──
echo "[INFO] Exécution des migrations..."
php artisan migrate --force
echo "[OK] Migrations terminées"

# ── Seeding (uniquement si la base est vide) ──
php artisan tinker --execute="exit(App\Models\User::count() > 0 ? 0 : 1);" 2>/dev/null
SEED_NEEDED=$?

if [ "$SEED_NEEDED" -ne 0 ]; then
    echo "[INFO] Base vide — Initialisation des données..."
    php artisan db:seed --force
    echo "[OK] Données initiales créées"
    echo "[INFO] Compte admin : admin@campus.ma / password"
    echo "[IMPORTANT] Changez le mot de passe après connexion !"
else
    echo "[OK] Base déjà initialisée"
fi

# ── Cache production ──
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "[OK] Cache production activé"

# ── Démarrage du serveur ──
echo "[INFO] Serveur démarré sur le port ${PORT:-8080}"
echo "════════════════════════════════════════"
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
