<?php

return [
    'cache_ttl' => intval(env('ONTOLOGIES_CACHE_TTL', 3600)),
    'base_uri' => env('ONTOLOGIES_BASE_URI', 'https://vocab.noeldemartin.com/'),
];
