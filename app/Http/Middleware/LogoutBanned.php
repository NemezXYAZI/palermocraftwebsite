<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/** Заблокований гравець не може користуватися сайтом навіть із відкритою сесією. */
class LogoutBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && $user->isBanned()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['email' => __('Акаунт заблоковано: :reason', ['reason' => $user->ban_reason ?: __('без пояснення')])]);
        }

        return $next($request);
    }
}
