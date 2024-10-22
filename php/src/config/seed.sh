#!/bin/sh

# Wait for PostgreSQL to be available
until php -r "new PDO('pgsql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    echo "Waiting for PostgreSQL..."
    sleep 2
done

# Run the seed script
php ./config/seed.php

# Start the Apache server in the foreground
apache2-foreground