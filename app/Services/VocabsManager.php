<?php

namespace App\Services;

use App\Http\Controllers\VocabController;
use App\Models\Vocab;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\SplFileInfo;

class VocabsManager
{
    /**
     * @var Vocab|null
     */
    private $current = null;

    /**
     * @return Collection<TKey, Vocab>
     */
    public function all(): Collection
    {
        return collect(File::allFiles(base_path('vocabs')))->map(function (SplFileInfo $file) {
            // TODO parse RDF classes
            return new Vocab($file->getFilenameWithoutExtension(), ['Foo', 'Bar']);
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
