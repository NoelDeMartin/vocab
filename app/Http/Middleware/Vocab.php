<?php

namespace App\Http\Middleware;

use App\Support\Facades\Vocabs;
use Closure;
use Illuminate\Http\Request;

class Vocab
{
    public function handle(Request $request, Closure $next, string $name)
    {
        Vocabs::current($name);

        return $next($request);
    }
}
