# frontend docker-entrypoint.sh

if [ ! -e ./.env ]; then
  cp .env.example .env
fi
