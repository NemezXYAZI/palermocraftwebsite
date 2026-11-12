<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Статус сервера через api.mcsrvstat.us (HTTPS працює на будь-якому cPanel, на відміну від прямого пінгу порту 25565).
 */
class ServerStatus
{
    /**
     * @return array{online: bool, players: int, max: int, version: ?string, address: string}
     */
    public function get(): array
    {
        $address = (string) config('palermo.server_address');

        return Cache::remember('palermo.server_status', 60, function () use ($address) {
            try {
                $data = Http::timeout(5)
                    ->withHeaders(['User-Agent' => 'PalermoCraft-Website/1.0'])
                    ->get('https://api.mcsrvstat.us/3/'.urlencode($address))
                    ->json();
            } catch (Throwable) {
                $data = null;
            }

            return [
                'online' => (bool) ($data['online'] ?? false),
                'players' => (int) ($data['players']['online'] ?? 0),
                'max' => (int) ($data['players']['max'] ?? 0),
                'version' => $data['version'] ?? null,
                'address' => $address,
            ];
        });
    }
}
