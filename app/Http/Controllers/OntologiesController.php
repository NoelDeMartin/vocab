<?php

namespace App\Http\Controllers;

use App\Support\Facades\Ontologies;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class OntologiesController extends Controller
{
    public function index(Request $request): View|Response
    {
        $ontology = Ontologies::current();

        if (is_null($ontology)) {
            abort(404);
        }

        if ($request->wantsRDF()) {
            return $ontology->rdfResponse($request);
        }

        return view('ontologies.index', compact('ontology'));
    }

    public function show(Request $request, string $shortId): View|RedirectResponse
    {
        $ontology = Ontologies::current();

        if (is_null($ontology)) {
            abort(404);
        }

        /** @var int|null $cacheTtl */
        $cacheTtl = config('ontologies.cache_ttl');

        $term = Cache::remember(
            "ontologies.{$ontology->shortId}.term.{$shortId}",
            $cacheTtl,
            fn () => $ontology->term($shortId)
        );

        if (is_null($term)) {
            abort(404);
        }

        if ($request->wantsRDF()) {
            return redirect($ontology->route(), 303);
        }

        return view('ontologies.show', compact('ontology', 'term'));
    }
}
