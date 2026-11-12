@extends('admin.layout')

@section('title', 'Скіни - Адмін-панель')

@section('admin')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Скіни</h2>
        <div class="flex gap-2 text-sm">
            @foreach (['pending' => 'На перевірці', 'approved' => 'Схвалені', 'rejected' => 'Відхилені'] as $key => $label)
                <a href="{{ route('admin.skins.index', ['status' => $key]) }}" class="px-3 py-1 rounded-xl {{ $status === $key ? 'bg-gray-800 text-palermo-green font-bold' : 'text-gray-400 hover:bg-gray-800' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    @unless (app(\App\Services\MineSkinClient::class)->isConfigured())
        <div class="mb-6 bg-yellow-900/20 border border-yellow-700/40 text-yellow-200 rounded-xl p-4 text-sm">
            MINESKIN_API_KEY не задано: схвалені скіни бачитимуть лише гравці з модом. Ключ: account.mineskin.org → API Keys.
        </div>
    @endunless

    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($skins as $skin)
            <div class="bg-palermo-card border border-gray-800 rounded-2xl p-4">
                <div class="flex gap-4">
                    <img src="{{ route('skins.preview', $skin) }}?v={{ $skin->hash }}" alt="" class="w-32 h-32 bg-gray-900 rounded-lg" style="image-rendering: pixelated">
                    <div class="text-sm min-w-0">
                        <a href="{{ route('admin.users.show', $skin->user) }}" class="font-bold hover:text-palermo-green">{{ $skin->user->minecraft_name ?? $skin->user->name }}</a>
                        <p class="text-gray-500 text-xs">{{ $skin->model }} · {{ $skin->updated_at->format('d.m H:i') }}</p>
                        @if ($skin->isApproved())
                            <p class="text-xs mt-2 {{ $skin->isSigned() ? 'text-palermo-green' : 'text-yellow-300' }}">{{ $skin->isSigned() ? 'Підписано (бачать усі)' : 'Без підпису' }}</p>
                            @if ($skin->sign_error)<p class="text-xs text-red-300 break-words">{{ $skin->sign_error }}</p>@endif
                        @endif
                        @if ($skin->rejection_reason)<p class="text-xs text-red-300 mt-2">{{ $skin->rejection_reason }}</p>@endif
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 mt-4">
                    @unless ($skin->isApproved())
                        <form method="POST" action="{{ route('admin.skins.approve', $skin) }}">@csrf
                            <button class="bg-palermo-green text-black font-bold px-3 py-1 rounded-lg text-sm">Схвалити</button>
                        </form>
                    @endunless
                    @if ($skin->isApproved() && ! $skin->isSigned())
                        <form method="POST" action="{{ route('admin.skins.resign', $skin) }}">@csrf
                            <button class="bg-gray-800 px-3 py-1 rounded-lg text-sm">Підписати ще раз</button>
                        </form>
                    @endif
                    @if ($skin->status !== 'rejected')
                        <form method="POST" action="{{ route('admin.skins.reject', $skin) }}" class="flex gap-2 flex-1">@csrf
                            <input name="reason" required maxlength="255" placeholder="Причина відмови" class="flex-1 min-w-0 bg-gray-900 border border-gray-700 px-2 py-1 rounded-lg text-sm">
                            <button class="bg-red-900/60 text-red-200 px-3 py-1 rounded-lg text-sm">Відхилити</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-500">Тут порожньо.</p>
        @endforelse
    </div>
    <div class="mt-4">{{ $skins->links() }}</div>
@endsection
