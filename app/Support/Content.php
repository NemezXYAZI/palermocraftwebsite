<?php

namespace App\Support;

/**
 * Тексти сайту (лор, можливості, правила, питання) лежать у resources/content/*.php —
 * їх можна правити без знання Blade. Кеш конфігурації їх не зачіпає.
 */
final class Content
{
    /** @var array<string, array<mixed>> */
    private static array $cache = [];

    public static function get(string $file, ?string $key = null, mixed $default = null): mixed
    {
        if (! isset(self::$cache[$file])) {
            $path = resource_path('content/'.$file.'.php');
            self::$cache[$file] = is_file($path) ? (array) require $path : [];
        }

        return $key === null ? self::$cache[$file] : data_get(self::$cache[$file], $key, $default);
    }
}
