<?php

namespace App\Http\Middleware;

use App\Support\Facades\Ontologies;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Ontology
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string $name): Response
    {
        Ontologies::current($name);

        return $next($request);
    }
}
