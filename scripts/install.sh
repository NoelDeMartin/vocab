#!/usr/bin/env bash

if [[ $(type -t vocab-cli) != function ]]; then
    echo "Don't call scripts directly, use the vocab binary!"

    exit;
fi

function ask_url() {
    while
        echo "What's the base url of your application?"
        read APP_URL

        if [ -z $APP_URL ]; then
            echo "The url cannot be empty"

            continue
        fi

        if [[ $APP_URL != http://* && $APP_URL != https://* ]]; then
            echo "The url should start with http:// or https://"

            continue
        fi

        break
    do true; done
}

function ask_nginx_agora() {
    while
        echo "Do you want to use nginx-agora? (y/n)"
        read USE_NGINX_AGORA

        if [[ $USE_NGINX_AGORA != y && $USE_NGINX_AGORA != n ]]; then
            echo "Please, respond with 'y' or 'n'."

            continue
        fi

        break
    do true; done
}

# Check if installing is necessary
if [ -f "$base_dir/.env" ]; then
    echo "Already installed!"
    exit
fi

# Abort and clean up on error
trap "clean_up" ERR

function clean_up() {
    if [ -d "$base_dir/public" ]; then
        rm $base_dir/public -rf
    fi

    if [ -d "$base_dir/nginx-agora" ]; then
        rm $base_dir/nginx-agora -rf
    fi

    if [ -f "$base_dir/.env" ]; then
        rm $base_dir/.env
    fi

    # TODO uninstall site from nginx-agora if installed

    exit
}

# Prepare .env
ask_url
ask_nginx_agora

ESCAPED_APP_URL=$(printf '%s\n' "$APP_URL" | sed -e 's/[\/&]/\\&/g')

cp .env.prod.example .env
sed s/APP_URL=/APP_URL=$ESCAPED_APP_URL/g -i .env

# Prepare assets
if [[ vocab_is_headless && ! -d "$base_dir/public" ]]; then
    mkdir "$base_dir/public"
fi

# Prepare nginx-agora
if [[ $USE_NGINX_AGORA == y ]]; then
    APP_DOMAIN=$(echo $APP_URL | sed -E s/https?:\\/\\///g)

    mkdir "$base_dir/nginx-agora"
    cp "$base_dir/docker/nginx/site.conf.template" "$base_dir/nginx-agora/$APP_DOMAIN.conf"
    sed "s/root \\/var\\/www\\/html/root \\/var\\/www\\/vocab/g" -i "$base_dir/nginx-agora/$APP_DOMAIN.conf"
    sed "s/fastcgi_pass app:9000/fastcgi_pass vocab:9000/g" -i "$base_dir/nginx-agora/$APP_DOMAIN.conf"
    sed s/\\[\\[APP_DOMAIN\\]\\]/$APP_DOMAIN/g -i "$base_dir/nginx-agora/$APP_DOMAIN.conf"

    nginx-agora install "$base_dir/nginx-agora/$APP_DOMAIN.conf" "$base_dir/public" vocab
fi

# Prepare storage
vocab-cli chown
vocab-docker-compose run app php artisan key:generate
vocab-docker-compose run app php artisan config:cache
vocab-docker-compose run app php artisan event:cache
vocab-docker-compose run app php artisan optimize
vocab-docker-compose run app php artisan route:cache
vocab-docker-compose run app php artisan view:cache

echo "Installed successfully!"
