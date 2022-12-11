#!/usr/bin/env bash

if [[ $(type -t vocab-cli) != function ]]; then
    echo "Don't call scripts directly, use the vocab binary!"

    exit;
fi

if vocab_is_using_nginx_agora; then
    nginx-agora disable vocab
    nginx-agora restart
fi

vocab-docker-compose down
