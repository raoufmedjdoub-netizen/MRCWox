#!/bin/bash
set -e

echo "==> Démarrage GPSWOX..."

# Attendre MySQL
echo "==> Attente MySQL..."
until php -r "new PDO('mysql:host=${DB_HOST};dbname=${web_database}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    echo "MySQL pas encore prêt, attente..."
    sleep 3
done
echo "==> MySQL connecté."

# Créer les dossiers storage requis (manquants après volume mount)
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

# Permissions avant artisan
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Package discovery (skippé au build)
php artisan package:discover --ansi || true

# Lien storage
php artisan storage:link --force || true

# Cache config
php artisan config:cache
php artisan route:clear
php artisan view:clear

# Migrations
echo "==> Migrations en cours..."
php artisan migrate --force --no-interaction

echo "==> Démarrage des services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
