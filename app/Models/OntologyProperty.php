<?php

namespace App\Models;

class OntologyProperty extends OntologyTerm
{
    /**
     * @var array<OntologyClass|string>
     */
    public $domain;

    /**
     * @var OntologyClass[]
     */
    public $range;

    /**
     * @param  array<OntologyClass|string>  $domain
     * @param  OntologyClass[]  $range
     */
    public function __construct(
        Ontology $ontology,
        string $id,
        string $name = '',
        string $description = '',
        array $domain = [],
        array $range = []
    ) {
        parent::__construct($ontology, $id, 'property', $name, $description);

        $this->domain = $domain;
        $this->range = $range;
    }

    public function addDomainClass(OntologyClass|string $class): void
    {
        $this->domain[] = $class;
    }

    public function addRangeClass(OntologyClass $class): void
    {
        $this->range[] = $class;
    }
}
