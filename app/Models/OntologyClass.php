<?php

namespace App\Models;

class OntologyClass implements OntologyTerm
{
    /**
     * @var Ontology
     */
    public $ontology;

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

    public function __construct(Ontology $ontology, string $id, string $name, string $description)
    {
        $this->ontology = $ontology;
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function shortId(): string
    {
        return substr($this->id, strlen($this->ontology->id));
    }
}
