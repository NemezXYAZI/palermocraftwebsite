<?php

namespace App\Http\Middleware;

use App\Support\Locale;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Глобальний middleware: працює для всіх запитів, зокрема для 404, щоб і сторінки помилок
 * говорили мовою відвідувача. Cookie з мовою не шифрується (див. bootstrap/app.php).
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Locale::detect($request);
        app()->setLocale($locale);
        Carbon::setLocale($locale);

        $response = $next($request);

        // Явний вибір через ?lang= запам'ятовуємо на рік
        $chosen = Locale::normalize($request->query('lang'));
        if ($chosen !== null && $chosen !== Locale::normalize($request->cookies->get(Locale::COOKIE))) {
            $response->headers->setCookie(Cookie::create(Locale::COOKIE, $chosen, now()->addYear(), '/', null, $request->isSecure(), true, false, Cookie::SAMESITE_LAX));
        }

        $response->headers->set('Content-Language', $locale);
        $response->setVary(array_unique(array_merge($response->getVary(), ['Accept-Language', 'Cookie'])));

        return $response;
    }
}
