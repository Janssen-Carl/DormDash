#!/bin/sh
set -e

echo "Fixing Laravel permissions..."

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "Waiting for MySQL server..."

until php -r "
try {
    new PDO(
        'mysql:host=${DB_HOST}',
        '${DB_USERNAME}',
        '${DB_PASSWORD}'
    );
} catch (Exception \$e) {
    exit(1);
}
"; do
  sleep 2
done

echo "Creating database if not exists..."

php -r "
try {
    \$pdo = new PDO(
        'mysql:host=${DB_HOST}',
        '${DB_USERNAME}',
        '${DB_PASSWORD}'
    );

    \$pdo->exec('CREATE DATABASE IF NOT EXISTS \`${DB_DATABASE}\`');

    echo 'Database ready.';
} catch (Exception \$e) {
    echo \$e->getMessage();
    exit(1);
}
"

echo "Running migrations..."

php artisan migrate --force

# Seed only when SEED_DB=true in the environment (safe default: do not seed in CI/containers)
if [ "${SEED_DB}" = "true" ]; then
  echo "Seeding database (SEED_DB=true)..."
  php artisan db:seed --force
else
  echo "Skipping database seeding (SEED_DB not true)"
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting PHP-FPM..."

exec "$@"
