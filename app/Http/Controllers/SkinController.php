<?php

namespace App\Http\Controllers;

use App\Models\Skin;
use App\Models\User;
use App\Services\SkinService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SkinController extends Controller
{
    public function show(Request $request)
    {
        return view('profile.skin', [
            'user' => $request->user(),
            'skin' => $request->user()->skin,
        ]);
    }

    public function upload(Request $request, SkinService $skins)
    {
        $data = $request->validate([
            'skin' => ['required', 'file', 'mimes:png', 'max:'.config('palermo.skins.max_kb')],
            'model' => ['required', 'in:classic,slim'],
        ]);

        $skin = $skins->upload($request->user(), $data['skin'], $data['model']);

        return back()->with('success', $skin->isApproved()
            ? 'Скін оновлено. У грі він зʼявиться одразу або після перезаходу.'
            : 'Скін завантажено й відправлено на перевірку модератору.');
    }

    public function destroy(Request $request, SkinService $skins)
    {
        $skin = $request->user()->skin;
        if ($skin) {
            $path = $skin->path;
            $skin->delete();
            if (! Skin::where('path', $path)->exists()) {
                Storage::disk('local')->delete($path);
            }
            $skins->notifyGame($request->user());
        }

        return back()->with('success', 'Скін видалено.');
    }

    /**
     * PNG за ніком гравця — цю адресу використовує клієнтський мод (palermo-skin-client / PalermoClient).
     * Віддаємо лише схвалені скіни.
     */
    public function png(string $name)
    {
        $user = User::where('minecraft_name', $name)->first();
        $skin = $user?->skin;
        abort_unless($skin && $skin->isApproved() && Storage::disk('local')->exists($skin->path), 404);

        return response(Storage::disk('local')->get($skin->path), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=300',
            'X-Skin-Model' => $skin->model,
        ]);
    }

    /** Попередній перегляд власного скіна (ще не схваленого) для сторінки профілю й модераторів. */
    public function preview(Request $request, Skin $skin)
    {
        abort_unless($request->user()->id === $skin->user_id || $request->user()->isStaff(), 403);
        abort_unless(Storage::disk('local')->exists($skin->path), 404);

        return response(Storage::disk('local')->get($skin->path), 200, ['Content-Type' => 'image/png']);
    }
}
