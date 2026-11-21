<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiteIsWritable
{
    /**
     * Block every write request while the site is in read-only mode.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('site.read_only') && ! $request->isMethodSafe()) {
            abort(503, 'Le site est en lecture seule pour le moment.');
        }

        return $next($request);
    }
}
