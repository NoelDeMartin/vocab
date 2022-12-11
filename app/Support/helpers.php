<?php

use App\Support\Markdown;

function markdown(string $text, bool $line = false): string
{
    return Markdown::render($text, $line);
}

function markdown_blade(string $key, array $replacements = [], bool $line = false): string
{
    return markdown(trans($key, $replacements), $line);
}
