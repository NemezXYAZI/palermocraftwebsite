<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Статус сервера через api.mcsrvstat.us (HTTPS працює на будь-якому cPanel, на відміну від прямого пінгу порту 25565).
 * Відповідь кешується на хвилину, а в межах одного запиту береться лише раз.
 */
class ServerStatus
{
    /** @var array{online: bool, players: int, max: int, version: ?string, address: string, list: list<string>}|null */
    private ?array $memo = null;

    /**
     * @return array{online: bool, players: int, max: int, version: ?string, address: string, list: list<string>}
     */
    public function get(): array
    {
        if ($this->memo !== null) {
            return $this->memo;
        }

        $address = (string) config('palermo.server_address');

        try {
            $status = Cache::remember('palermo.server_status.v2', 60, fn () => $this->fetch($address));
        } catch (Throwable) {
            // Кеш у базі недоступний (наприклад, під час оновлення) — питаємо напряму
            $status = $this->fetch($address);
        }

        return $this->memo = $status;
    }

    private function fetch(string $address): array
    {
        try {
            $data = Http::connectTimeout(2)
                ->timeout(4)
                ->withHeaders(['User-Agent' => 'PalermoCraft-Website/2.0'])
                ->get('https://api.mcsrvstat.us/3/'.urlencode($address))
                ->json();
        } catch (Throwable) {
            $data = null;
        }

        $names = [];
        foreach ((array) ($data['players']['list'] ?? []) as $player) {
            $name = is_array($player) ? ($player['name'] ?? null) : $player;
            if (is_string($name) && preg_match('/^[A-Za-z0-9_]{1,16}$/', $name)) {
                $names[] = $name;
            }
        }

        return [
            'online' => (bool) ($data['online'] ?? false),
            'players' => (int) ($data['players']['online'] ?? 0),
            'max' => (int) ($data['players']['max'] ?? 0),
            'version' => isset($data['version']) ? (string) $data['version'] : null,
            'address' => $address,
            'list' => array_slice($names, 0, 24),
        ];
    }
}
