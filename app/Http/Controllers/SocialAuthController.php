<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TelegramLoginVerifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Discord OAuth2 і Telegram Login Widget.
 * Залогінений гравець — привʼязує акаунт; гість — входить, якщо соцмережа вже привʼязана.
 */
class SocialAuthController extends Controller
{
    public function discordRedirect(Request $request)
    {
        abort_unless(config('services.discord.client_id'), 404);

        $state = Str::random(40);
        $request->session()->put('discord_oauth_state', $state);

        $query = http_build_query([
            'client_id' => config('services.discord.client_id'),
            'redirect_uri' => config('services.discord.redirect'),
            'response_type' => 'code',
            'scope' => 'identify',
            'state' => $state,
            'prompt' => 'none',
        ]);

        return redirect('https://discord.com/oauth2/authorize?'.$query);
    }

    public function discordCallback(Request $request)
    {
        $expected = $request->session()->pull('discord_oauth_state');
        if (! $expected || ! hash_equals($expected, (string) $request->query('state')) || ! $request->filled('code')) {
            return $this->fail($request, 'Discord: запит застарів або був скасований. Спробуйте ще раз.');
        }

        $token = Http::asForm()->timeout(10)->post('https://discord.com/api/oauth2/token', [
            'client_id' => config('services.discord.client_id'),
            'client_secret' => config('services.discord.client_secret'),
            'grant_type' => 'authorization_code',
            'code' => $request->query('code'),
            'redirect_uri' => config('services.discord.redirect'),
        ]);
        if (! $token->successful()) {
            return $this->fail($request, 'Discord не підтвердив вхід. Спробуйте ще раз.');
        }

        $me = Http::withToken($token->json('access_token'))->timeout(10)->get('https://discord.com/api/users/@me');
        if (! $me->successful()) {
            return $this->fail($request, 'Не вдалося отримати профіль Discord.');
        }

        $discordId = (string) $me->json('id');
        $username = $me->json('global_name') ?: $me->json('username');

        return $this->linkOrLogin($request, 'discord_id', $discordId, [
            'discord_id' => $discordId,
            'discord_username' => $username,
            'discord_linked_at' => now(),
        ], 'Discord');
    }

    public function telegramCallback(Request $request, TelegramLoginVerifier $verifier)
    {
        if (! $verifier->verify($request->query())) {
            return $this->fail($request, 'Telegram: підпис не пройшов перевірку. Спробуйте ще раз.');
        }

        $telegramId = (int) $request->query('id');
        $username = $request->query('username')
            ? '@'.$request->query('username')
            : trim($request->query('first_name').' '.$request->query('last_name'));

        return $this->linkOrLogin($request, 'telegram_id', $telegramId, [
            'telegram_id' => $telegramId,
            'telegram_username' => $username,
            'telegram_linked_at' => now(),
        ], 'Telegram');
    }

    private function linkOrLogin(Request $request, string $column, string|int $id, array $fields, string $label)
    {
        $owner = User::where($column, $id)->first();
        $current = $request->user();

        if ($current) {
            if ($owner && $owner->id !== $current->id) {
                return $this->fail($request, $label.' уже привʼязаний до іншого акаунта сайту.');
            }
            $current->update($fields);

            return redirect()->route('profile.index')->with('success', $label.' успішно привʼязано.');
        }

        if (! $owner) {
            return $this->fail($request, 'Цей '.$label.' ще не привʼязаний. Зареєструйтесь і привʼяжіть його в профілі.');
        }
        if ($owner->isBanned()) {
            return $this->fail($request, 'Акаунт заблоковано: '.($owner->ban_reason ?: 'без пояснення'));
        }

        Auth::login($owner, remember: true);
        $request->session()->regenerate();

        return redirect()->route('profile.index');
    }

    private function fail(Request $request, string $message)
    {
        return redirect()->route($request->user() ? 'profile.index' : 'login')->withErrors(['social' => $message]);
    }
}
