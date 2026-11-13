<?php

namespace App\Services;

use App\Models\Skin;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class SkinService
{
    public function __construct(private readonly MineSkinClient $mineSkin) {}

    /**
     * Зберігає новий скін гравця. Якщо модерацію вимкнено — одразу підписує й повідомляє сервер.
     */
    public function upload(User $user, UploadedFile $file, string $model): Skin
    {
        $bytes = file_get_contents($file->getRealPath());
        $size = @getimagesizefromstring($bytes);
        if ($size === false || $size[2] !== IMAGETYPE_PNG) {
            throw ValidationException::withMessages(['skin' => __('Файл має бути PNG-зображенням.')]);
        }
        [$width, $height] = $size;
        if (! ($width === 64 && in_array($height, [32, 64], true))) {
            throw ValidationException::withMessages(['skin' => __('Розмір скіна має бути 64×64 або 64×32 пікселі.')]);
        }

        $hash = sha1($bytes);
        $path = 'skins/'.$hash.'.png';
        Storage::disk('local')->put($path, $bytes);

        $previous = $user->skin;
        $skin = Skin::updateOrCreate(
            ['user_id' => $user->id],
            [
                'model' => $model === 'slim' ? 'slim' : 'classic',
                'path' => $path,
                'hash' => $hash,
                'status' => Skin::STATUS_PENDING,
                // Той самий файл і модель уже підписані — не витрачаємо ліміт MineSkin
                'texture_value' => ($previous && $previous->hash === $hash && $previous->model === $model) ? $previous->texture_value : null,
                'texture_signature' => ($previous && $previous->hash === $hash && $previous->model === $model) ? $previous->texture_signature : null,
                'sign_error' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'rejection_reason' => null,
            ]
        );

        if ($previous && $previous->path !== $path && ! Skin::where('path', $previous->path)->exists()) {
            Storage::disk('local')->delete($previous->path);
        }

        if (! config('palermo.skins.require_approval') || $user->isStaff()) {
            $this->approve($skin, $user->isStaff() ? $user : null);
        }

        return $skin->fresh();
    }

    public function approve(Skin $skin, ?User $reviewer): void
    {
        $skin->forceFill([
            'status' => Skin::STATUS_APPROVED,
            'reviewed_by' => $reviewer?->id,
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ])->save();

        $this->sign($skin);
        $this->notifyGame($skin->user);
    }

    public function reject(Skin $skin, User $reviewer, string $reason): void
    {
        $skin->forceFill([
            'status' => Skin::STATUS_REJECTED,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ])->save();

        $this->notifyGame($skin->user);
    }

    /** Підпис через MineSkin. Без ключа скін усе одно працює для гравців із модом PalermoClient. */
    public function sign(Skin $skin): void
    {
        if ($skin->isSigned() || ! $this->mineSkin->isConfigured()) {
            return;
        }
        try {
            $signed = $this->mineSkin->sign(Storage::disk('local')->get($skin->path), $skin->model);
            $skin->forceFill([
                'texture_value' => $signed['value'],
                'texture_signature' => $signed['signature'],
                'sign_error' => null,
            ])->save();
        } catch (Throwable $e) {
            $skin->forceFill(['sign_error' => mb_substr($e->getMessage(), 0, 255)])->save();
            Log::warning('Skin signing failed', ['skin' => $skin->id, 'error' => $e->getMessage()]);
        }
    }

    /** Миттєве оновлення в грі (необов'язково: інакше скін підтягнеться при наступному вході гравця). */
    public function notifyGame(?User $user): void
    {
        $url = config('palermo.game_webhook_url');
        if (blank($url) || ! $user?->minecraft_name) {
            return;
        }
        try {
            Http::timeout(3)
                ->withHeaders(['X-Palermo-Token' => (string) config('palermo.game_api_token')])
                ->post(rtrim($url, '/').'/api/website/skin-updated?player='.urlencode($user->minecraft_name));
        } catch (Throwable $e) {
            Log::info('Game webhook unreachable', ['error' => $e->getMessage()]);
        }
    }
}
