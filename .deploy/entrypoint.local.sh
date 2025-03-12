#!/bin/bash
set -e

USER_ID=$(stat -c "%u" /var/www/html)
GROUP_ID=$(stat -c "%g" /var/www/html)

if [ "$USER_ID" -eq 0 ]; then
    echo "Updating ownership of /var/www/html to match WSL user..."
    chown -R $(id -u):$(id -g) /var/www/html
fi

chmod -R 775 /var/www/html

composer install --optimize-autoloader

exec "$@"
