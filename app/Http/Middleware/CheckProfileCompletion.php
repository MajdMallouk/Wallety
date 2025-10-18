<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileCompletion
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            if (! auth()->user()->completed_profile) {
                return redirect()->route('profile.complete');
            }
        }

        return $next($request);
    }
}
