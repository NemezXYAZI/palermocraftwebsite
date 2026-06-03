<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

    public function linkDiscord(Request $request)
    {
        $data = $request->validate([
            'discord_code' => ['required', 'string', 'size:6'],
        ]);

        $request->user()->update([
            'discord_username' => 'Discord #' . strtoupper($data['discord_code']),
            'discord_linked_at' => now(),
        ]);

        return back()->with('success', 'Discord акаунт успішно привʼязано.');
    }

    public function unlinkDiscord(Request $request)
    {
        $request->user()->update([
            'discord_username' => null,
            'discord_linked_at' => null,
        ]);

        return back()->with('success', 'Discord акаунт відвʼязано.');
    }

    public function linkTelegram(Request $request)
    {
        $data = $request->validate([
            'telegram_code' => ['required', 'string', 'size:6'],
        ]);

        $request->user()->update([
            'telegram_username' => '@telegram_' . strtoupper($data['telegram_code']),
            'telegram_linked_at' => now(),
        ]);

        return back()->with('success', 'Telegram акаунт успішно привʼязано.');
    }

    public function unlinkTelegram(Request $request)
    {
        $request->user()->update([
            'telegram_username' => null,
            'telegram_linked_at' => null,
        ]);

        return back()->with('success', 'Telegram акаунт відвʼязано.');
    }

    public function topUp(Request $request)
    {
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
