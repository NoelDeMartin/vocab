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
        /** @var int|null $cacheTtl */
        $cacheTtl = config('ontologies.cache_ttl');

        /** @var Ontology[] */
        return Cache::remember('ontologies.all', $cacheTtl, function (): array {
            $files = File::allFiles(resource_path('ontologies'));

            return Arr::map($files, fn (SplFileInfo $file) => $this->parseOntology($file));
        });
    }

    public function current(?string $shortId = null): ?Ontology
    {
        if ($shortId) {
            $this->current = Arr::first($this->all(), fn ($ontology) => $ontology->shortId === $shortId);
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
        $graph = new Graph;
        $parser = new TurtleParser;
        /** @var array<string, OntologyClass> $extraneousClasses */
        $extraneousClasses = [];
        $name = $file->getFilenameWithoutExtension();
        /** @var string $baseUriPrefix */
        $baseUriPrefix = config('ontologies.base_uri');
        $baseUri = $baseUriPrefix.$name.'/';

        $parser->parse($graph, $file->getContents(), 'turtle', $baseUri);

        return tap(new Ontology($baseUri, $graph, $parser->getNamespaces()), function (Ontology $ontology) use ($graph, &$extraneousClasses) {
            /** @var \EasyRdf\Resource[] $classResources */
            $classResources = $graph->allOfType('<http://www.w3.org/2000/01/rdf-schema#Class>');
            /** @var \EasyRdf\Resource[] $propertyResources */
            $propertyResources = $graph->allOfType('<http://www.w3.org/1999/02/22-rdf-syntax-ns#Property>');

            foreach ($classResources as $classResource) {
                /** @var string $classUri */
                $classUri = $classResource->getUri();
                /** @var string $label */
                $label = $classResource->getLiteral('<http://www.w3.org/2000/01/rdf-schema#label>')->getValue();
                /** @var string $description */
                $description = $classResource->getLiteral('<http://purl.org/dc/terms/description>')->getValue();

                $class = new OntologyClass(
                    $ontology,
                    $classUri,
                    $label,
                    $description
                );

                $ontology->addClass($class);

                /** @var \EasyRdf\Resource|null $parentClassResource */
                $parentClassResource = $classResource->getResource('<http://www.w3.org/2000/01/rdf-schema#subClassOf>');
                $parentClassUri = $parentClassResource?->getUri();
                $parentClass = $parentClassUri ? $ontology->class($parentClassUri) : null;

                if (is_null($parentClass)) {
                    continue;
                }

                $parentClass->addChildClass($class);
                $class->setParentClass($parentClass);
            }

            foreach ($propertyResources as $propertyResource) {
                $linked = false;
                /** @var \EasyRdf\Resource[] $domainClasses */
                $domainClasses = $propertyResource->all('<http://www.w3.org/2000/01/rdf-schema#domain>');
                /** @var \EasyRdf\Resource[] $rangeClasses */
                $rangeClasses = $propertyResource->all('<http://www.w3.org/2000/01/rdf-schema#range>');
                /** @var string $propertyUri */
                $propertyUri = $propertyResource->getUri();
                /** @var string $propertyLabel */
                $propertyLabel = $propertyResource->getLiteral('<http://www.w3.org/2000/01/rdf-schema#label>')->getValue();
                /** @var string $propertyDescription */
                $propertyDescription = $propertyResource->getLiteral('<http://purl.org/dc/terms/description>')->getValue();

                $property = new OntologyProperty(
                    $ontology,
                    $propertyUri,
                    $propertyLabel,
                    $propertyDescription
                );

                foreach ($domainClasses as $domainClassValue) {
                    /** @var \EasyRdf\Resource[] $resolvedDomainClasses */
                    $resolvedDomainClasses = [];

                    if ($domainClassValue->isBNode()) {
                        /** @var \EasyRdf\Resource|null $list */
                        $list = $domainClassValue->get('<http://www.w3.org/2002/07/owl#unionOf>');

                        while ($list !== null && ($first = $list->get('<http://www.w3.org/1999/02/22-rdf-syntax-ns#first>')) !== null) {
                            /** @var \EasyRdf\Resource $first */
                            $resolvedDomainClasses[] = $first;
                            /** @var \EasyRdf\Resource|null $list */
                            $list = $list->get('<http://www.w3.org/1999/02/22-rdf-syntax-ns#rest>');
                        }
                    } else {
                        $resolvedDomainClasses[] = $domainClassValue;
                    }

                    foreach ($resolvedDomainClasses as $domainClass) {
                        /** @var string $domainClassUri */
                        $domainClassUri = $domainClass->getUri();
                        $class = $ontology->class($domainClassUri);

                        if (is_null($class)) {
                            $property->addDomainClass($domainClassUri);

                            continue;
                        }

                        $linked = true;
                        $class->addProperty($property);
                        $property->addDomainClass($class);
                    }
                }

                foreach ($rangeClasses as $rangeClass) {
                    /** @var string $classUri */
                    $classUri = $rangeClass->getUri();
                    $class = $ontology->class($classUri);

                    if (is_null($class)) {
                        $extraneousClasses[$classUri] ??= new OntologyClass($ontology, $classUri);

                        $property->addRangeClass($extraneousClasses[$classUri]);

                        continue;
                    }

                    $linked = true;
                    $property->addRangeClass($class);
                }

                if (! $linked) {
                    $ontology->addOrphanProperty($property);
                }
            }
        });
    }
}
