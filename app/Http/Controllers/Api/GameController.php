<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MinecraftLinkCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * API для плагінів (palermo-skins / palermo-sync). Захищено заголовком X-Palermo-Token.
 */
class GameController extends Controller
{
    /** Усе, що сервер має знати про гравця під час входу: скін, бан, прив'язки для 2FA. */
    public function player(string $name): JsonResponse
    {
        $user = User::with('skin')->where('minecraft_name', $name)->first();
        if (! $user) {
            return response()->json(['linked' => false, 'name' => $name]);
        }

        $skin = $user->skin && $user->skin->isApproved() ? $user->skin : null;

        return response()->json([
            'linked' => true,
            'name' => $user->minecraft_name,
            'account' => $user->name,
            'uuid' => $user->minecraft_uuid,
            'banned' => $user->isBanned(),
            'ban_reason' => $user->ban_reason,
            'role' => $user->role,
            // рядками: Discord snowflake не влазить у double
            'discord_id' => $user->discord_id !== null ? (string) $user->discord_id : null,
            'telegram_id' => $user->telegram_id !== null ? (string) $user->telegram_id : null,
            'skin' => $skin ? [
                'model' => $skin->model,
                'png_url' => route('skins.png', ['name' => $user->minecraft_name]),
                'hash' => $skin->hash,
                'value' => $skin->texture_value,
                'signature' => $skin->texture_signature,
            ] : null,
        ]);
    }

    /** Гравець написав у грі /sitelink КОД — привʼязуємо нік до акаунта сайту. */
    public function link(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:12'],
            'name' => ['required', 'string', 'regex:/^[A-Za-z0-9_]{3,16}$/'],
            'uuid' => ['nullable', 'uuid'],
        ]);

        return DB::transaction(function () use ($data) {
            $code = MinecraftLinkCode::where('code', strtoupper($data['code']))->lockForUpdate()->first();
            if (! $code || ! $code->isUsable()) {
                return response()->json(['ok' => false, 'error' => 'invalid_code'], 422);
            }

            $taken = User::where('minecraft_name', $data['name'])->where('id', '!=', $code->user_id)->exists();
            if ($taken) {
                return response()->json(['ok' => false, 'error' => 'name_taken'], 409);
            }

            $user = $code->user;
            $user->update([
                'minecraft_name' => $data['name'],
                'minecraft_uuid' => $data['uuid'] ?? null,
                'minecraft_linked_at' => now(),
            ]);
            $code->update(['used_at' => now()]);

            return response()->json(['ok' => true, 'account' => $user->name]);
        });
    }
}
