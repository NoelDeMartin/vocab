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
    public $shortId;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string[]
     */
    public $namespaces;

    /**
     * @var OntologyClass[]
     */
    public $classes;

    /**
     * @param  string  $id
     * @param  string  $name
     * @param  string  $description
     * @param  string[]  $namespaces
     * @param  OntologyClass[]  $classes
     */
    public function __construct(string $id, string $name, string $description, array $namespaces, array $classes = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->namespaces = $namespaces;
        $this->classes = $classes;

        $this->shortId = substr($this->id, strlen(config('ontologies.base_uri')), -1);
    }

    public function class(string $id): ?OntologyClass
    {
        return Arr::first($this->classes, fn ($class) => $class->id === $id || $class->shortId === $id);
    }

    public function term(string $id): ?OntologyTerm
    {
        foreach ($this->classes as $class) {
            if ($class->id === $id || $class->shortId === $id) {
                return $class;
            }

            $property = Arr::first($class->properties, fn ($property) => $property->id === $id || $property->shortId === $id);

            if (is_null($property)) {
                continue;
            }

            return $property;
        }

        return null;
    }

    public function route(string $name, ...$args): string
    {
        return route("ontologies.{$this->shortId}.".$name, ...$args);
    }

    public function addClass(OntologyClass $class): void
    {
        $this->classes[] = $class;
    }
}
