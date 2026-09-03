<?php

namespace App\Support;

use Parsedown;

class Markdown extends Parsedown
{
    public static function render(string $text, bool $line = false): string
    {
        $parser = new Markdown;

        /** @var string $rendered */
        $rendered = $line ? $parser->line($text) : $parser->text($text);

        return $rendered;
    }

    /**
     * @param  array<string, mixed>  $excerpt
     * @return array<string, mixed>|null
     */
    protected function inlineLink($excerpt): ?array
    {
        /** @var array<string, mixed>|null $result */
        $result = parent::inlineLink($excerpt);

        if (is_null($result) || ! is_array($result['element'] ?? null)) {
            return $result;
        }

        /** @var array<string, mixed> $element */
        $element = $result['element'];
        /** @var array<string, mixed> $attributes */
        $attributes = is_array($element['attributes'] ?? null) ? $element['attributes'] : [];

        $url = is_string($attributes['href'] ?? null) ? $attributes['href'] : '';

        if (! str_starts_with($url, '#') && ! str_starts_with($url, url(''))) {
            $attributes['target'] = '_blank';
        }

        $element['attributes'] = $attributes;
        $result['element'] = $element;

        return $result;
    }
}
