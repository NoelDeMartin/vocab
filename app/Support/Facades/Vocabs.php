<?php

namespace App\Support\Facades;

use App\Models\Vocab;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Collection<TKey, Vocab> all();
 * @method static Vocab|null current(?string $name);
 * @method static void routes();
 *
 * @see \App\Services\VocabsManager
 */
class Vocabs extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vocabs';
    }
}
