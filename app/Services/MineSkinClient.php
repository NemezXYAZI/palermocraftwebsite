<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Підпис скіна через MineSkin (https://docs.mineskin.org).
 *
 * Ванільні клієнти — і ліцензійні, і піратські — показують лише скіни, підписані Mojang.
 * MineSkin завантажує PNG на свій акаунт Mojang і повертає пару value/signature,
 * яку плагін palermo-skins накладає на гравця в грі.
 */
class MineSkinClient
{
    private const ENDPOINT = 'https://api.mineskin.org/v2/generate';

    public function isConfigured(): bool
    {
        return filled(config('services.mineskin.key'));
    }

    /**
     * @return array{value: string, signature: string}
     */
    public function sign(string $pngBytes, string $variant): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('MINESKIN_API_KEY не задано.');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('services.mineskin.key'),
            'User-Agent' => config('services.mineskin.user_agent'),
        ])
            ->timeout(45)
            ->attach('file', $pngBytes, 'skin.png', ['Content-Type' => 'image/png'])
            ->post(self::ENDPOINT, [
                'variant' => $variant === 'slim' ? 'slim' : 'classic',
                'visibility' => 'unlisted',
            ]);

        if (! $response->successful()) {
            $message = $response->json('errors.0.message') ?? $response->json('error') ?? ('HTTP '.$response->status());
            throw new RuntimeException('MineSkin: '.$message);
        }

        // v2: skin.texture.data.{value,signature}; v1-сумісність: data.texture.{value,signature}
        $value = $response->json('skin.texture.data.value') ?? $response->json('data.texture.value');
        $signature = $response->json('skin.texture.data.signature') ?? $response->json('data.texture.signature');

        if (blank($value) || blank($signature)) {
            throw new RuntimeException('MineSkin повернув відповідь без підпису.');
        }

        return ['value' => $value, 'signature' => $signature];
    }
}
