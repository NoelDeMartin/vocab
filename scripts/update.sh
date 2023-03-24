#!/usr/bin/env bash

if [[ $(type -t vocab-cli) != function ]]; then
    echo "Don't call scripts directly, use the vocab binary!"

    exit;
fi

# Abort on errors
set -e

# Pull new code
git -C $base_dir pull

# Update nginx-agora
# TODO if nginx-agora is configured, regenerate and copy nginx config

# Update containers
vocab-docker-compose pull

if vocab_is_running; then
    vocab-cli restart
    vocab-docker-compose exec app php artisan config:cache
    vocab-docker-compose exec app php artisan event:cache
    vocab-docker-compose exec app php artisan optimize
    vocab-docker-compose exec app php artisan route:cache
    vocab-docker-compose exec app php artisan view:cache
else
    vocab-docker-compose run app php artisan config:cache
    vocab-docker-compose run app php artisan event:cache
    vocab-docker-compose run app php artisan optimize
    vocab-docker-compose run app php artisan route:cache
    vocab-docker-compose run app php artisan view:cache
fi

vocab-cli chown

echo "Updated successfully!"
