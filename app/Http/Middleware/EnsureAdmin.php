<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->is_active || ! in_array($request->user()->role, ['admin', 'super_admin', 'admin_event', 'admin_education', 'admin_gallery', 'admin_learning'], true)) {
            abort(403, 'Akses hanya untuk admin KADESTIM.');
        }

        return $next($request);
    }
}
