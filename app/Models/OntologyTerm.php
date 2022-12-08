<?php

namespace App\Models;

use EasyRdf\RdfNamespace;

abstract class OntologyTerm
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
    public $type;

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

    public function __construct(
        Ontology $ontology,
        string $id,
        string $type,
        string $name = '',
        string $description = ''
    ) {
        $this->ontology = $ontology;
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->description = $description;

        $this->shortId = $this->shortenId($id, $ontology->namespaces)
            ?? $this->shortenId($id, RdfNamespace::namespaces())
            ?? $id;
    }

    /**
     * @param  string  $id
     * @param  string[]  $namespaces
     */
    protected function shortenId(string $id, array $namespaces): ?string
    {
        foreach ($namespaces as $namespace => $prefix) {
            if (! str_starts_with($id, $prefix)) {
                continue;
            }

            $shortId = substr($id, strlen($prefix));

            return empty($namespace) ? $shortId : "$namespace:$shortId";
        }

        return null;
    }
}
