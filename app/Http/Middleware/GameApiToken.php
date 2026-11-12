<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GameApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('palermo.game_api_token');
        $given = (string) $request->header('X-Palermo-Token');

        if ($expected === '' || ! hash_equals($expected, $given)) {
            return response()->json(['error' => 'unauthorized'], 401);
        }

        return $next($request);
    }
}
