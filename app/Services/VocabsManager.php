<?php

namespace App\Services;

use App\Http\Controllers\VocabController;
use App\Models\Vocab;
use EasyRdf\Graph;
use EasyRdf\Parser\Turtle;
use EasyRdf\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\SplFileInfo;

class VocabsManager
{
    private const CACHE_TTL = 3600; // 1 hour

    private const VOCABS_PREFIX = 'https://vocab.noeldemartin.com/';

    /**
     * @var Vocab|null
     */
    private $current = null;

    /**
     * @return Collection<TKey, Vocab>
     */
    public function all(): Collection
    {
        return Cache::remember('vocabs', static::CACHE_TTL, function () {
            $parser = new Turtle();

            return collect(File::allFiles(base_path('vocabs')))->map(function (SplFileInfo $file) use ($parser) {
                $name = $file->getFilenameWithoutExtension();
                $baseUri = static::VOCABS_PREFIX.$name.'/';
                $graph = tap(new Graph(), fn (Graph $graph) => $parser->parse($graph, $file->getContents(), 'turtle', $baseUri));
                $classes = collect($graph->allOfType('http://www.w3.org/2000/01/rdf-schema#Class'))->map(
                    fn (Resource $resource) => substr($resource->getUri(), strlen($baseUri))
                );

                return new Vocab($name, $classes);
            });
        });
    }

    public function current(?string $name = null): ?Vocab
    {
        if ($name) {
            $this->current = static::all()->firstWhere('name', $name);
        }

        return $this->current;
    }

    public function routes(): void
    {
        foreach (static::all() as $vocab) {
            Route::resource($vocab->name, VocabController::class, ['as' => 'vocabs'])
                ->only('index', 'show')
                ->middleware("vocab:{$vocab->name}");
        }
    }
}
