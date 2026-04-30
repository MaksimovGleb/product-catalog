#!/bin/sh

# Создание .env если его нет
if [ ! -f ".env" ]; then
    echo "Creating .env file from .env.example"
    cp .env.example .env
fi

# Ожидание готовности базы данных
until nc -z -v -w30 db 5432
do
  echo "Waiting for database connection..."
  sleep 5
done

# Генерация ключа, если его нет
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --no-interaction
fi

# Запуск миграций
php artisan migrate --force

# Автоматический сидинг, если база пуста
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" | tr -d '\r\n')
if [ "$USER_COUNT" = "0" ]; then
    echo "Database is empty (0 users found). Running seeders..."
    php artisan db:seed --force
else
    echo "Database already contains data ($USER_COUNT users found). Skipping seeders."
fi

# Запуск PHP-FPM
php-fpm
