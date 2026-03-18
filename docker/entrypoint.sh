#!/bin/bash
set -e

echo "==> Démarrage GPSWOX..."

# Attendre MySQL
echo "==> Attente MySQL..."
until php -r "new PDO('mysql:host=${DB_HOST};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    echo "MySQL pas encore prêt, attente..."
    sleep 3
done
echo "==> MySQL connecté."

# Package discovery (skippé au build)
php artisan package:discover --ansi || true

# Lien storage
php artisan storage:link --force || true

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:clear

# Migrations
echo "==> Migrations en cours..."
php artisan migrate --force --no-interaction

# Permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "==> Démarrage des services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
