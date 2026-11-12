<?php

namespace App\Services;

/**
 * Перевірка даних Telegram Login Widget: https://core.telegram.org/widgets/login#checking-authorization
 */
class TelegramLoginVerifier
{
    /**
     * @param  array<string, mixed>  $data  усі параметри з callback, включно з hash
     */
    public function verify(array $data): bool
    {
        $token = (string) config('services.telegram.bot_token');
        $hash = $data['hash'] ?? null;
        if ($token === '' || ! is_string($hash)) {
            return false;
        }

        $fields = array_intersect_key($data, array_flip(['id', 'first_name', 'last_name', 'username', 'photo_url', 'auth_date']));
        ksort($fields);
        $checkString = collect($fields)->map(fn ($v, $k) => $k.'='.$v)->implode("\n");

        $secret = hash('sha256', $token, true);
        $expected = hash_hmac('sha256', $checkString, $secret);

        if (! hash_equals($expected, $hash)) {
            return false;
        }

        // Дані не старші доби — захист від повторного використання
        return (time() - (int) ($data['auth_date'] ?? 0)) < 86400;
    }
}
