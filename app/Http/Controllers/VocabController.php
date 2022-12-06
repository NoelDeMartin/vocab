<?php

namespace App\Http\Controllers;

use App\Support\Facades\Vocabs;

class VocabController extends Controller
{
    public function index()
    {
        return view('vocabs.index')
            ->with('vocab', Vocabs::current());
    }

    public function show(string $term)
    {
        return view('vocabs.show')
            ->with('vocab', Vocabs::current())
            ->with('term', $term);
    }
}
