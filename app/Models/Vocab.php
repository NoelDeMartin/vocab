<?php

namespace App\Models;

use Illuminate\Support\Collection;

class Vocab
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var Collection<TKey, string>
     */
    public $classes;

    /**
     * @param  string  $name
     * @param  string[]  $classes
     */
    public function __construct(string $name, Collection $classes)
    {
        $this->name = $name;
        $this->classes = $classes;
    }

    public function hasTerm(string $term): bool
    {
        return $this->classes->contains($term);
    }
}
