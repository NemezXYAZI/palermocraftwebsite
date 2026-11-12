<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStaff
{
    public function handle(Request $request, Closure $next, string $level = 'staff'): Response
    {
        $user = $request->user();
        $allowed = $level === 'admin' ? $user?->isAdmin() : $user?->isStaff();
        abort_unless($allowed, 403);

        return $next($request);
    }
}
