<?php

namespace App\Services;

use App\Http\Controllers\OntologiesController;
use App\Models\Ontology;
use App\Models\OntologyClass;
use App\Models\OntologyProperty;
use App\Support\Rdf\TurtleParser;
use EasyRdf\Graph;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\SplFileInfo;

class OntologiesManager
{
    /**
     * @var Ontology|null
     */
    private $current = null;

    /**
     * @return Ontology[]
     */
    public function all(): array
    {
        return Cache::remember('ontologies.all', config('ontologies.cache_ttl'), function () {
            $files = File::allFiles(resource_path('ontologies'));

            return Arr::map($files, fn (SplFileInfo $file) => $this->parseOntology($file));
        });
    }

    public function current(?string $shortId = null): ?Ontology
    {
        if ($shortId) {
            $this->current = Arr::first(static::all(), fn ($ontology) => $ontology->shortId === $shortId);
        }

        return $this->current;
    }

    public function routes(): void
    {
        foreach (static::all() as $ontology) {
            Route::resource($ontology->shortId, OntologiesController::class, ['as' => 'ontologies'])
                ->only('index', 'show')
                ->middleware("ontology:{$ontology->shortId}");
        }
    }

    protected function parseOntology(SplFileInfo $file): Ontology
    {
        $graph = new Graph();
        $parser = new TurtleParser();
        $extraneousClasses = [];
        $name = $file->getFilenameWithoutExtension();
        $baseUri = config('ontologies.base_uri').$name.'/';

        $parser->parse($graph, $file->getContents(), 'turtle', $baseUri);

        return tap(new Ontology(
            $baseUri,
            $graph->getLiteral($baseUri, '<http://www.w3.org/2000/01/rdf-schema#label>')->getValue(),
            $graph->getLiteral($baseUri, '<http://purl.org/dc/terms/description>')->getValue(),
            $parser->getNamespaces()
        ), function ($ontology) use ($graph, $extraneousClasses) {
            $classResources = $graph->allOfType('<http://www.w3.org/2000/01/rdf-schema#Class>');
            $propertyResources = $graph->allOfType('<http://www.w3.org/1999/02/22-rdf-syntax-ns#Property>');

            foreach ($classResources as $classResource) {
                $class = new OntologyClass(
                    $ontology,
                    $classResource->getUri(),
                    $classResource->getLiteral('<http://www.w3.org/2000/01/rdf-schema#label>')->getValue(),
                    $classResource->getLiteral('<http://purl.org/dc/terms/description>')->getValue()
                );

                $ontology->addClass($class);

                $parentClassUri = $classResource->getResource('<http://www.w3.org/2000/01/rdf-schema#subClassOf>')?->getUri();
                $parentClass = $parentClassUri ? $ontology->class($parentClassUri) : null;

                if (is_null($parentClass)) {
                    continue;
                }

                $parentClass->addChildClass($class);
                $class->setParentClass($parentClass);
            }

            foreach ($propertyResources as $propertyResource) {
                $domainClasses = $propertyResource->all('<http://www.w3.org/2000/01/rdf-schema#domain>');
                $rangeClasses = $propertyResource->all('<http://www.w3.org/2000/01/rdf-schema#range>');
                $property = new OntologyProperty(
                    $ontology,
                    $propertyResource->getUri(),
                    $propertyResource->getLiteral('<http://www.w3.org/2000/01/rdf-schema#label>')->getValue(),
                    $propertyResource->getLiteral('<http://purl.org/dc/terms/description>')->getValue()
                );

                foreach ($domainClasses as $domainClass) {
                    $class = $ontology->class($domainClass->getUri());

                    if (is_null($class)) {
                        continue;
                    }

                    $class->addProperty($property);
                    $property->addDomainClass($class);
                }

                foreach ($rangeClasses as $rangeClass) {
                    $classUri = $rangeClass->getUri();
                    $class = $ontology->class($classUri);

                    if (is_null($class)) {
                        $extraneousClasses[$classUri] ??= new OntologyClass($ontology, $classUri);

                        $property->addRangeClass($extraneousClasses[$classUri]);

                        continue;
                    }

                    $property->addRangeClass($class);
                }
            }
        });
    }
}
