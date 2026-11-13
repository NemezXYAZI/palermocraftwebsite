<?php

namespace App\Support;

/**
 * Іконки Lucide (ISC, resources/icons/LICENSE), вбудовані прямо в HTML:
 * без шрифтів-іконок і зайвих запитів.
 */
final class Icons
{
    /** @var array<string, string> */
    private static array $cache = [];

    public static function get(string $name): string
    {
        return self::$cache[$name] ??= self::load($name);
    }

    /** Логотипи Discord і Telegram (Simple Icons, CC0): resources/icons/brands. */
    public static function brand(string $name): string
    {
        return self::$cache['brand:'.$name] ??= self::load('brands/'.$name);
    }

    private static function load(string $name): string
    {
        if (! preg_match('#^(brands/)?[a-z0-9-]+$#', $name)) {
            return '';
        }
        $file = resource_path('icons/'.$name.'.svg');

        return is_file($file) ? trim((string) file_get_contents($file)) : '';
    }
}
