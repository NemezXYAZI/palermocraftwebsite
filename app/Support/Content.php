<?php

namespace App\Support;

/**
 * Тексти сайту (лор, можливості, правила, питання, моди) лежать у resources/content/*.php —
 * їх можна правити без знання Blade. Кеш конфігурації їх не зачіпає.
 *
 * Українська — основа. Переклад лежить у resources/content/{en,ru}/<файл>.php і містить лише
 * тексти з тією самою структурою: він накладається поверх основи. Чого в перекладі немає
 * (нове правило, новий мод), те показується українською, доки його не перекладуть.
 */
final class Content
{
    /** @var array<string, array<mixed>> */
    private static array $cache = [];

    public static function get(string $file, ?string $key = null, mixed $default = null): mixed
    {
        $locale = Locale::current();
        $cacheKey = $locale.':'.$file;

        if (! isset(self::$cache[$cacheKey])) {
            $data = self::load($file);
            if ($locale !== Locale::DEFAULT) {
                $overlay = self::load($locale.'/'.$file);
                if ($overlay !== []) {
                    $data = array_replace_recursive($data, $overlay);
                }
            }
            self::$cache[$cacheKey] = $data;
        }

        return $key === null ? self::$cache[$cacheKey] : data_get(self::$cache[$cacheKey], $key, $default);
    }

    /** @return array<mixed> */
    private static function load(string $file): array
    {
        $path = resource_path('content/'.$file.'.php');

        return is_file($path) ? (array) require $path : [];
    }
}
