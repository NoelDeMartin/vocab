<?php

namespace App\Http\Controllers;

use App\Support\Facades\Ontologies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OntologiesController extends Controller
{
    public function index(Request $request)
    {
        $ontology = Ontologies::current();

        if ($request->wantsRDF()) {
            return $ontology->rdfResponse($request);
        }

        return view('ontologies.index', compact('ontology'));
    }

    public function show(Request $request, string $shortId)
    {
        $ontology = Ontologies::current();
        $term = Cache::remember(
            "ontologies.{$ontology->shortId}.term.{$shortId}",
            config('ontologies.cache_ttl'),
            fn () => $ontology->term($shortId)
        );

        if (is_null($term)) {
            return abort(404);
        }

        if ($request->wantsRDF()) {
            return redirect($ontology->route(), 303);
        }

        return view('ontologies.show', compact('ontology', 'term'));
    }
}
