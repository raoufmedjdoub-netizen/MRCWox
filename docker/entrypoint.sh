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

# Créer la base gpswox_traccar si elle n'existe pas (requise par les migrations traccar_mysql)
echo "==> Vérification base gpswox_traccar..."
php -r "
try {
    \$pdo = new PDO('mysql:host=${DB_HOST:-mysql};port=${DB_PORT:-3306}', '${DB_USERNAME}', '${DB_PASSWORD}');
    \$pdo->exec('CREATE DATABASE IF NOT EXISTS \`gpswox_traccar\` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    // Créer la table devices si elle n'existe pas (migrations ALTER en ont besoin)
    \$pdo->exec('CREATE TABLE IF NOT EXISTS \`gpswox_traccar\`.\`devices\` (
        \`id\` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        \`name\` varchar(255) DEFAULT NULL,
        \`uniqueId\` varchar(255) DEFAULT NULL,
        \`latestPosition_id\` bigint(20) unsigned DEFAULT NULL,
        \`lastValidLatitude\` double DEFAULT NULL,
        \`lastValidLongitude\` double DEFAULT NULL,
        \`device_time\` datetime DEFAULT NULL,
        \`server_time\` datetime DEFAULT NULL,
        \`ack_time\` datetime DEFAULT NULL,
        \`time\` datetime DEFAULT NULL,
        \`speed\` double DEFAULT NULL,
        \`other\` text DEFAULT NULL,
        \`altitude\` double DEFAULT NULL,
        \`power\` double DEFAULT NULL,
        \`course\` double DEFAULT NULL,
        \`address\` varchar(255) DEFAULT NULL,
        \`protocol\` varchar(50) DEFAULT NULL,
        \`latest_positions\` text DEFAULT NULL,
        PRIMARY KEY (\`id\`),
        UNIQUE KEY \`uniqueId\` (\`uniqueId\`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
    echo 'gpswox_traccar OK' . PHP_EOL;
} catch(Exception \$e) {
    echo 'gpswox_traccar warning: ' . \$e->getMessage() . PHP_EOL;
}
" 2>/dev/null || true

# Migrations
echo "==> Migrations en cours..."
# Supprimer les migrations échouées pour permettre le re-run
php -r "
try {
    \$pdo = new PDO('mysql:host=${DB_HOST:-mysql};dbname=${web_database}', '${DB_USERNAME}', '${DB_PASSWORD}');
    \$pdo->exec(\"DELETE FROM migrations WHERE migration = '2026_03_18_000000_seed_tracker_ports_defaults' AND batch IN (SELECT batch FROM (SELECT MAX(batch) as batch FROM migrations) t)\");
} catch(Exception \$e) {}
" 2>/dev/null || true
php artisan migrate --force --no-interaction

# Repermissions après artisan (les commandes artisan créent des fichiers en root)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "==> Démarrage des services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
