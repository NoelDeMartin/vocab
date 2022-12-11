<?php

namespace App\Models;

class OntologyClass extends OntologyTerm
{
    /**
     * @var OntologyProperty[]
     */
    public $properties;

    /**
     * @var OntologyClass|null
     */
    public $parentClass = null;

    /**
     * @var OntologyClass[]
     */
    public $childClasses = [];

    /**
     * @param  Ontology  $ontology
     * @param  string  $id
     * @param  string  $name
     * @param  string  $description
     * @param  OntologyProperty[]  $properties
     */
    public function __construct(
        Ontology $ontology,
        string $id,
        string $name = '',
        string $description = '',
        array $properties = []
    ) {
        parent::__construct($ontology, $id, 'class', $name, $description);

        $this->properties = $properties;
    }

    public function isExtraneous(): bool
    {
        return ! str_starts_with($this->id, $this->ontology->id);
    }

    /**
     * @return OntologyClass[]
     */
    public function hierarchy(): array
    {
        $classes = [];

        for ($class = $this; ! is_null($class); $class = $class->parentClass) {
            $classes[] = $class;
        }

        return $classes;
    }

    public function addProperty(OntologyProperty $property): void
    {
        $this->properties[] = $property;
    }

    public function setParentClass(OntologyClass $class): void
    {
        $this->parentClass = $class;
    }

    public function addChildClass(OntologyClass $class): void
    {
        $this->childClasses[] = $class;
    }
}
