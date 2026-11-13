<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $users = User::query()
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('minecraft_name', 'like', "%{$q}%")
                    ->orWhere('discord_username', 'like', "%{$q}%")
                    ->orWhere('telegram_username', 'like', "%{$q}%");
            }))
            ->when($request->query('filter') === 'banned', fn ($query) => $query->whereNotNull('banned_at'))
            ->when($request->query('filter') === 'staff', fn ($query) => $query->whereIn('role', [User::ROLE_MODERATOR, User::ROLE_ADMIN]))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', ['target' => $user->load('skin')]);
    }

    public function updateRole(Request $request, User $user)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $data = $request->validate(['role' => ['required', Rule::in(User::ROLES)]]);

        if ($user->is($request->user()) && $data['role'] !== User::ROLE_ADMIN) {
            return back()->withErrors(['role' => 'Не можна зняти з себе роль адміністратора.']);
        }

        $user->forceFill(['role' => $data['role']])->save();

        return back()->with('success', 'Роль змінено.');
    }

    public function ban(Request $request, User $user)
    {
        $data = $request->validate(['reason' => ['required', 'string', 'max:255']]);
        if ($user->isStaff() && ! $request->user()->isAdmin()) {
            abort(403, 'Модератор не може блокувати команду проєкту.');
        }
        if ($user->is($request->user())) {
            return back()->withErrors(['reason' => 'Не можна заблокувати самого себе.']);
        }

        $user->forceFill(['banned_at' => now(), 'ban_reason' => $data['reason']])->save();

        return back()->with('success', 'Гравця заблоковано на сайті й у грі (при наступному вході).');
    }

    public function unban(User $user)
    {
        $user->forceFill(['banned_at' => null, 'ban_reason' => null])->save();

        return back()->with('success', 'Блокування знято.');
    }
}
