<?php

namespace App\Support\Facades;

use App\Models\Ontology;
use App\Services\OntologiesManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Ontology[] all()
 * @method static Ontology|null current(string|null $shortId=null)
 * @method static void routes()
 *
 * @see OntologiesManager
 */
class Ontologies extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ontologies';
    }
}
