<?php

namespace App\Support;

use Parsedown;

class Markdown extends Parsedown
{
    public static function render($text, $line = false)
    {
        $parser = new static;

        return $line ? $parser->line($text) : $parser->text($text);
    }

    protected function inlineLink($excerpt)
    {
        $result = parent::inlineLink($excerpt);

        $result['element']['attributes'] = $result['element']['attributes'] ?? [];

        $url = $result['element']['attributes']['href'] ?? '';

        if (! str_starts_with($url, '#') && ! str_starts_with($url, url(''))) {
            $result['element']['attributes']['target'] = '_blank';
        }

        return $result;
    }
}
