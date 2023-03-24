#!/usr/bin/env bash

if [[ $(type -t vocab-cli) != function ]]; then
    echo "Don't call scripts directly, use the vocab binary!"

    exit;
fi

nginx-agora disable vocab
nginx-agora restart
vocab-docker-compose down
