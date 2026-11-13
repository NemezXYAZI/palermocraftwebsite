<?php

namespace App\Http\Controllers;

use App\Models\MinecraftLinkCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    public function updateUsername(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:32',
                Rule::unique('users', 'name')->ignore($user->id),
            ],
        ]);

        $user->update([
            'name' => $data['name'],
        ]);

        return back()->with('success', 'Логін успішно змінено.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Поточний пароль введено неправильно.',
            ]);
        }

        $user->update([
            'password' => $data['password'],
        ]);

        return back()->with('success', 'Пароль успішно змінено.');
    }


    public function unlinkDiscord(Request $request)
    {
        $request->user()->update([
            'discord_id' => null,
            'discord_username' => null,
            'discord_linked_at' => null,
        ]);

        return back()->with('success', 'Discord акаунт відвʼязано.');
    }

    public function unlinkTelegram(Request $request)
    {
        $request->user()->update([
            'telegram_id' => null,
            'telegram_username' => null,
            'telegram_linked_at' => null,
        ]);

        return back()->with('success', 'Telegram акаунт відвʼязано.');
    }

    /** Код для команди /sitelink у грі: підтверджує, що нік справді належить власнику акаунта. */
    public function minecraftCode(Request $request)
    {
        $user = $request->user();
        MinecraftLinkCode::where('user_id', $user->id)->whereNull('used_at')->delete();

        do {
            $code = strtoupper(Str::random(8));
        } while (MinecraftLinkCode::where('code', $code)->exists());

        MinecraftLinkCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(config('palermo.link_code_ttl_minutes')),
        ]);

        return back()->with('link_code', $code);
    }

    public function minecraftUnlink(Request $request)
    {
        $request->user()->update([
            'minecraft_name' => null,
            'minecraft_uuid' => null,
            'minecraft_linked_at' => null,
        ]);

        return back()->with('success', 'Ігровий акаунт відвʼязано.');
    }
}
