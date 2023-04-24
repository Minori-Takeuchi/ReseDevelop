# backend docker-entrypoint.sh

if [ ! -e ./.env ]; then
  cp .env.example .env
fi

crontab -u www-data -l | { cat; echo "0 10 * * * cd /var/www/laravel && php artisan users:send_mail >> /dev/null 2>&1"; } | crontab -u www-data -

php artisan serve --host=0.0.0.0