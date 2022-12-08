<?php

namespace App\Http\Controllers;

use App\Support\Facades\Ontologies;
use Illuminate\Support\Facades\Cache;

class OntologiesController extends Controller
{
    public function index()
    {
        $ontology = Ontologies::current();

        return view('ontologies.index', compact('ontology'));
    }

    public function show(string $shortId)
    {
        $ontology = Ontologies::current();
        $term = Cache::remember(
            "ontologies.{$ontology->shortId}.term.{$shortId}",
            config('ontologies.cache_ttl'),
            fn () => $ontology->term($shortId)
        );

        if (is_null($term)) {
            abort(404);
        }

        return view('ontologies.show', compact('ontology', 'term'));
    }
}
