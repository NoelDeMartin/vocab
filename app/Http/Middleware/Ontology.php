<?php

namespace App\Http\Middleware;

use App\Support\Facades\Ontologies;
use Closure;
use Illuminate\Http\Request;

class Ontology
{
    public function handle(Request $request, Closure $next, string $name)
    {
        Ontologies::current($name);

        return $next($request);
    }
}
