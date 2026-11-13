<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\MinecraftLinkCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $inventoryItems = $user->inventoryItems()
            ->latest()
            ->get();

        return view('profile.index', [
            'user' => $user,
            'inventoryItems' => $inventoryItems,
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

    public function verifyEmail(Request $request)
    {
        $user = $request->user();

        if ($user->email_verified_at) {
            return back()->with('success', 'Email вже підтверджено.');
        }

        $user->update([
            'email_verified_at' => now(),
        ]);

        return back()->with('success', 'Email успішно підтверджено.');
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

    public function topUp(Request $request)
    {
        // Справжньої оплати ще немає: «поповнення» дозволене лише адмінам і в локальній розробці
        abort_unless($request->user()->isAdmin() || app()->isLocal(), 403, 'Онлайн-оплату ще не підключено.');

        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:10', 'max:100000'],
        ]);

        $request->user()->increment('balance', $data['amount']);

        return back()->with('success', 'Баланс успішно поповнено на ' . $data['amount'] . ' монет.');
    }

    public function activateInventoryItem(Request $request, InventoryItem $item)
    {
        $user = $request->user();

        if ($item->user_id !== $user->id) {
            abort(403);
        }

        if ($item->status !== 'active') {
            return back()->withErrors([
                'activate' => 'Цей предмет вже активовано, повернено або він недоступний для активації.',
            ]);
        }

        $item->update([
            'status' => 'activated',
            'description' => trim(($item->description ?: '') . ' Активовано на сервері.')
        ]);

        return back()->with('success', 'Предмет успішно активовано на сервері.');
    }

    public function refund(Request $request, InventoryItem $item)
    {
        $user = $request->user();

        if ($item->user_id !== $user->id) {
            abort(403);
        }

        if ($item->status !== 'active') {
            return back()->withErrors([
                'refund' => 'Цей предмет вже був повернений або недоступний для повернення.',
            ]);
        }

        DB::transaction(function () use ($user, $item) {
            $item->update([
                'status' => 'refunded',
                'refunded_at' => now(),
            ]);

            $user->increment('balance', $item->price);
        });

        return back()->with('success', 'Предмет повернено. На баланс зараховано ' . $item->price . ' монет.');
    }

}
