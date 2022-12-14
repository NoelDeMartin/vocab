#!/usr/bin/env bash

if [[ $(type -t vocab-cli) != function ]]; then
    echo "Don't call scripts directly, use the vocab binary!"

    exit;
fi

vocab-docker-compose up -d

if vocab_is_headless; then
    vocab-cli publish-assets
fi

if vocab_is_using_nginx_agora; then
    nginx-agora enable vocab
    nginx-agora restart
fi
