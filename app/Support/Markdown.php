<?php

namespace App\Support;

use Parsedown;

class Markdown extends Parsedown
{

    public static function render($text) {
        return (new static)->text($text);
    }

    protected function inlineLink($excerpt)
    {
        $result = parent::inlineLink($excerpt);

        $result['element']['attributes'] = $result['element']['attributes'] ?? [];

        if (! str_starts_with($result['element']['attributes']['href'] ?? '', '#')) {
            $result['element']['attributes']['target'] = '_blank';
        }

        return $result;
    }

}
