<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skin;
use App\Services\SkinService;
use Illuminate\Http\Request;

class SkinController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', Skin::STATUS_PENDING);
        $skins = Skin::with('user')
            ->when(in_array($status, [Skin::STATUS_PENDING, Skin::STATUS_APPROVED, Skin::STATUS_REJECTED], true),
                fn ($q) => $q->where('status', $status))
            ->latest('updated_at')
            ->paginate(24)
            ->withQueryString();

        return view('admin.skins.index', compact('skins', 'status'));
    }

    public function approve(Request $request, Skin $skin, SkinService $service)
    {
        $service->approve($skin, $request->user());

        return back()->with('success', $skin->fresh()->isSigned()
            ? 'Скін схвалено й підписано — його бачитимуть усі гравці.'
            : 'Скін схвалено. Підпис MineSkin не отримано'.($skin->fresh()->sign_error ? ' ('.$skin->fresh()->sign_error.')' : '').' — поки що його бачать лише гравці з модом.');
    }

    public function reject(Request $request, Skin $skin, SkinService $service)
    {
        $data = $request->validate(['reason' => ['required', 'string', 'max:255']]);
        $service->reject($skin, $request->user(), $data['reason']);

        return back()->with('success', 'Скін відхилено.');
    }

    public function resign(Skin $skin, SkinService $service)
    {
        $skin->forceFill(['texture_value' => null, 'texture_signature' => null])->save();
        $service->sign($skin);
        $service->notifyGame($skin->user);

        return back()->with('success', $skin->fresh()->isSigned() ? 'Скін підписано повторно.' : 'Підписати не вдалося: '.$skin->fresh()->sign_error);
    }
}
