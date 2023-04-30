# backend docker-entrypoint.sh

if [ ! -e ./.env ]; then
  cp .env.example .env
fi

crond
php artisan serve --host=0.0.0.0
