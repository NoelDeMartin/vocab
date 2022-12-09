#!/usr/bin/env bash

if [[ $(type -t vocab-cli) != function ]]; then
    echo "Don't call scripts directly, use the vocab binary!"

    exit;
fi

if ! vocab_is_running; then
    echo "App is not running!"

    exit
fi

service=${1:-app}

vocab-docker-compose exec $service sh
