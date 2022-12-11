<?php

use App\Support\Markdown;

function markdown(string $text): string {
    return Markdown::render($text);
}
