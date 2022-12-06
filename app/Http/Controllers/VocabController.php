<?php

namespace App\Http\Controllers;

use App\Support\Facades\Vocabs;

class VocabController extends Controller
{
    public function index()
    {
        return view('vocabs.index')->with('vocab', Vocabs::current());
    }

    public function show(string $term)
    {
        $vocab = Vocabs::current();

        if (! $vocab->hasTerm($term)) {
            abort(404);
        }

        return view('vocabs.show', compact('vocab', 'term'));
    }
}
