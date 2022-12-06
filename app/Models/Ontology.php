<?php

namespace App\Models;

use Illuminate\Support\Arr;

class Ontology
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var OntologyClass[]
     */
    public $classes;

    /**
     * @param  string  $id
     * @param  string  $name
     * @param  string  $description
     * @param  OntologyClass[]  $classes
     */
    public function __construct(string $id, string $name, string $description, array $classes = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->classes = $classes;
    }

    public function shortId(): string
    {
        return substr($this->id, strlen(config('ontologies.base_uri')), -1);
    }

    public function term(string $shortId): ?OntologyTerm
    {
        return Arr::first($this->classes, fn ($class) => $class->shortId() === $shortId);
    }

    public function addClass(OntologyClass $class): void
    {
        $this->classes[] = $class;
    }
}
