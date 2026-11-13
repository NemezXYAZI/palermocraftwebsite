<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Мови сайту. Українська — основна: її тексти лежать прямо у views і resources/content,
 * англійська й російська — у lang/{en,ru}.json та resources/content/{en,ru}/.
 *
 * Мову обирає ?lang=… (і запам'ятовує в cookie), далі cookie, далі мова браузера.
 */
final class Locale
{
    public const DEFAULT = 'uk';

    public const COOKIE = 'palermo_lang';

    /** @var array<string, array{name: string, short: string, og: string}> */
    public const SUPPORTED = [
        'uk' => ['name' => 'Українська', 'short' => 'UA', 'og' => 'uk_UA'],
        'en' => ['name' => 'English', 'short' => 'EN', 'og' => 'en_US'],
        'ru' => ['name' => 'Русский', 'short' => 'RU', 'og' => 'ru_RU'],
    ];

    /** «ua», «uk-UA», «EN_us» → код мови сайту або null. */
    public static function normalize(?string $code): ?string
    {
        if ($code === null || $code === '') {
            return null;
        }
        $code = strtolower(substr(str_replace('_', '-', trim($code)), 0, 2));
        if ($code === 'ua') {
            $code = 'uk';
        }

        return isset(self::SUPPORTED[$code]) ? $code : null;
    }

    public static function detect(Request $request): string
    {
        if ($fromQuery = self::normalize($request->query('lang'))) {
            return $fromQuery;
        }
        if ($fromCookie = self::normalize($request->cookies->get(self::COOKIE))) {
            return $fromCookie;
        }
        foreach ($request->getLanguages() as $preferred) {
            if ($fromBrowser = self::normalize($preferred)) {
                return $fromBrowser;
            }
        }

        return self::DEFAULT;
    }

    public static function current(): string
    {
        return self::normalize(app()->getLocale()) ?? self::DEFAULT;
    }

    /** Поточна сторінка іншою мовою: посилання для перемикача (завжди з ?lang). */
    public static function switchUrl(string $locale): string
    {
        return self::withLang(request()->fullUrl(), $locale);
    }

    /** Адреса сторінки певною мовою для hreflang і canonical: українська — без параметра. */
    public static function pageUrl(string $locale): string
    {
        $url = self::withLang(request()->url().self::queryWithoutLang(), null);

        return $locale === self::DEFAULT ? $url : self::withLang($url, $locale);
    }

    private static function queryWithoutLang(): string
    {
        $query = request()->query();
        unset($query['lang']);

        return $query === [] ? '' : '?'.http_build_query($query);
    }

    private static function withLang(string $url, ?string $locale): string
    {
        $parts = parse_url($url);
        parse_str($parts['query'] ?? '', $query);
        unset($query['lang']);
        if ($locale !== null) {
            $query['lang'] = $locale;
        }
        $base = strtok($url, '?');
        $fragment = isset($parts['fragment']) ? '#'.$parts['fragment'] : '';

        return $base.($query === [] ? '' : '?'.http_build_query($query)).$fragment;
    }
}
