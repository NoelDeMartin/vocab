<?php

namespace App\Services;

use App\Http\Controllers\OntologiesController;
use App\Models\Ontology;
use App\Models\OntologyClass;
use EasyRdf\Graph;
use EasyRdf\Parser\Turtle;
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
        return Cache::remember('ontologies', config('ontologies.cache_ttl'), function () {
            $parser = new Turtle();
            $files = File::allFiles(resource_path('ontologies'));

            return Arr::map($files, fn (SplFileInfo $file) => $this->parseOntology($file));
        });
    }

    public function current(?string $shortId = null): ?Ontology
    {
        if ($shortId) {
            $this->current = Arr::first(static::all(), fn ($ontology) => $ontology->shortId() === $shortId);
        }

        return $this->current;
    }

    public function routes(): void
    {
        foreach (static::all() as $ontology) {
            Route::resource($ontology->shortId(), OntologiesController::class, ['as' => 'ontologies'])
                ->only('index', 'show')
                ->middleware("ontology:{$ontology->shortId()}");
        }
    }

    protected function parseOntology(SplFileInfo $file): Ontology
    {
        $graph = new Graph();
        $parser = new Turtle();
        $name = $file->getFilenameWithoutExtension();
        $baseUri = config('ontologies.base_uri').$name.'/';

        $parser->parse($graph, $file->getContents(), 'turtle', $baseUri);

        return tap(new Ontology(
            $baseUri,
            $graph->getLiteral($baseUri, '<http://www.w3.org/2000/01/rdf-schema#label>')->getValue(),
            $graph->getLiteral($baseUri, '<http://purl.org/dc/terms/description>')->getValue()
        ), function ($ontology) use ($graph) {
            $classResources = $graph->allOfType('<http://www.w3.org/2000/01/rdf-schema#Class>');

            foreach ($classResources as $resource) {
                $ontology->addClass(
                    new OntologyClass(
                        $ontology,
                        $resource->getUri(),
                        $resource->getLiteral('<http://www.w3.org/2000/01/rdf-schema#label>')->getValue(),
                        $resource->getLiteral('<http://purl.org/dc/terms/description>')->getValue()
                    ),
                );
            }
        });
    }
}
