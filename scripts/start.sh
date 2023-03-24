#!/usr/bin/env bash

if [[ $(type -t vocab-cli) != function ]]; then
    echo "Don't call scripts directly, use the vocab binary!"

    exit;
fi

vocab-docker-compose up -d
vocab-cli publish-assets
nginx-agora enable vocab
nginx-agora restart
