#!/usr/bin/env bash

if [[ $(type -t vocab-cli) != function ]]; then
    echo "Don't call scripts directly, use the vocab binary!"

    exit;
fi

if ! vocab_is_running; then
    echo "Can't publish assets if app is not running!"

    exit
fi

containerid=`vocab-docker-compose ps --quiet app`
containername=`docker ps --filter "id=$containerid" --format="{{.Names}}"`

rm $base_dir/public/* -rf
docker cp "$containername:/app/public/." "$base_dir/public"
