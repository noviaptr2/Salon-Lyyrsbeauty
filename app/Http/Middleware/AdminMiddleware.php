<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        abort_unless($request->user()->role === 'admin', 403, 'Halaman ini hanya bisa diakses admin.');

        return $next($request);
    }
}
