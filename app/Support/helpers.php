<?php

use App\Support\Markdown;

function markdown(string $text, bool $line = false): string
{
    return Markdown::render($text, $line);
}

/**
 * @param  array<string, string>  $replacements
 */
function markdown_blade(string $key, array $replacements = [], bool $line = false): string
{
    /** @var string $translated */
    $translated = trans($key, $replacements);

    return markdown($translated, $line);
}
