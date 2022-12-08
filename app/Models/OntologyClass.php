<?php

namespace App\Models;

class OntologyClass extends OntologyTerm
{
    /**
     * @var OntologyProperty[]
     */
    public $properties;

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

    public function addProperty(OntologyProperty $property): void
    {
        $this->properties[] = $property;
    }
}
