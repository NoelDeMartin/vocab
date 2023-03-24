<?php

namespace App\Support\Facades;

use App\Models\Ontology;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Collection<TKey, Ontology> all();
 * @method static Ontology|null current(string|null $shortId=null);
 * @method static void routes();
 *
 * @see \App\Services\OntologiesManager
 */
class Ontologies extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ontologies';
    }
}
