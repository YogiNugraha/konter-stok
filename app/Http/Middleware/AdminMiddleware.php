<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika 'gerbang' is-admin tidak terbuka untuk user ini
        if (! Gate::allows('is-admin')) {
            // Hentikan proses dan tampilkan halaman error 403 (Forbidden)
            abort(403);
        }

        // Jika gerbang terbuka, lanjutkan ke halaman yang dituju
        return $next($request);
    }
}
