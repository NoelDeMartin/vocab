<?php

namespace App\Models;

class Vocab
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string[]
     */
    public $classes;

    /**
     * @param  string  $name
     * @param  string[]  $classes
     */
    public function __construct(string $name, array $classes)
    {
        $this->name = $name;
        $this->classes = $classes;
    }
}
