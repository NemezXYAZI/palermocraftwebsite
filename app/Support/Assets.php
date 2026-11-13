<?php

namespace App\Support;

/**
 * Адреси статичних файлів з міткою версії: після оновлення сайту браузери
 * одразу беруть новий CSS/JS, а не старий із кешу.
 */
final class Assets
{
    /** @var array<string, string> */
    private static array $versions = [];

    public static function url(string $path): string
    {
        $path = ltrim($path, '/');
        $version = self::$versions[$path] ??= self::version($path);

        return asset($path).($version !== '' ? '?v='.$version : '');
    }

    private static function version(string $path): string
    {
        $file = public_path($path);

        return is_file($file) ? substr(md5((string) filemtime($file).filesize($file)), 0, 10) : '';
    }
}
