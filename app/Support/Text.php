<?php

namespace App\Support;

final class Text
{
    /** Українська множина: 1 гравець, 2 гравці, 5 гравців. */
    public static function plural(int $n, string $one, string $few, string $many): string
    {
        $mod100 = $n % 100;
        $mod10 = $n % 10;
        if ($mod100 >= 11 && $mod100 <= 14) {
            return $many;
        }

        return match (true) {
            $mod10 === 1 => $one,
            $mod10 >= 2 && $mod10 <= 4 => $few,
            default => $many,
        };
    }

    /** Короткий опис із тексту новини: без HTML і зайвих пробілів. */
    public static function excerpt(?string $text, int $limit = 220): string
    {
        $plain = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $text)) ?? '');

        return \Illuminate\Support\Str::limit($plain, $limit);
    }
}
