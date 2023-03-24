<?php

namespace App\Models;

use EasyRdf\Graph;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * @var Graph
     */
    public $graph;

    /**
     * @var string[]
     */
    public $namespaces;

    /**
     * @var OntologyClass[]
     */
    public $classes = [];

    /**
     * @param  string[]  $namespaces
     */
    public function __construct(string $baseUri, Graph $graph, array $namespaces)
    {
        $this->id = $baseUri;
        $this->name = $graph->getLiteral($baseUri, '<http://www.w3.org/2000/01/rdf-schema#label>')->getValue();
        $this->description = $graph->getLiteral($baseUri, '<http://purl.org/dc/terms/description>')->getValue();
        $this->graph = $graph;
        $this->namespaces = $namespaces;
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

    public function route($nameOrTerm = 'index', ...$args): string
    {
        if ($nameOrTerm instanceof OntologyTerm) {
            return $this->route('show', $nameOrTerm->shortId);
        }

        return route("ontologies.{$this->shortId}.".$nameOrTerm, ...$args);
    }

    public function rdfResponse(Request $request): Response
    {
        $serializedOntology = $this->graph->serialise($request->rdfFormat());

        return response($serializedOntology, 200, [
            'Content-Type' => $request->rdfContentType(),
        ]);
    }

    public function addClass(OntologyClass $class): void
    {
        $this->classes[] = $class;
    }
}
