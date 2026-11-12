@extends('layouts.app')

@section('title', 'Мій скін - PalermoCraft')

@section('content')
    <main class="flex-grow py-10 px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-5xl mx-auto">
            <a href="{{ route('profile.index') }}" class="text-sm text-gray-400 hover:text-palermo-green">← До профілю</a>
            <h2 class="text-3xl font-bold text-palermo-green mb-8 text-center">Мій скін</h2>

            @include('partials.flash')

            <div class="grid md:grid-cols-2 gap-8">
                <section class="bg-palermo-card border border-gray-800 rounded-2xl p-6 flex flex-col items-center">
                    @if ($skin)
                        <canvas id="skin-viewer" class="rounded-xl bg-gray-900"></canvas>
                        <div class="mt-4 text-center">
                            @switch($skin->status)
                                @case('approved')
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-palermo-green/10 text-palermo-green border border-palermo-green/40">Схвалено</span>
                                    @break
                                @case('pending')
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-yellow-900/30 text-yellow-300 border border-yellow-700/40">На перевірці</span>
                                    @break
                                @default
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-900/30 text-red-300 border border-red-700/40">Відхилено</span>
                                    @if ($skin->rejection_reason)
                                        <p class="text-xs text-red-300 mt-2">{{ $skin->rejection_reason }}</p>
                                    @endif
                            @endswitch
                            <p class="text-xs text-gray-500 mt-2">Модель: {{ $skin->model === 'slim' ? 'Alex (тонкі руки)' : 'Steve (класична)' }}</p>
                            @if ($skin->isApproved() && ! $skin->isSigned())
                                <p class="text-xs text-yellow-300 mt-2">Поки що скін бачать лише гравці з модом PalermoClient.</p>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('profile.skin.destroy') }}" class="mt-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-400 hover:text-red-300">Видалити скін</button>
                        </form>
                    @else
                        <div class="h-80 flex items-center justify-center text-gray-500 text-center">
                            Скін ще не завантажено.<br>У грі буде стандартний вигляд.
                        </div>
                    @endif
                </section>

                <section class="bg-palermo-card border border-gray-800 rounded-2xl p-6">
                    <h3 class="text-xl font-bold mb-4">Завантажити скін</h3>
                    @unless ($user->minecraft_name)
                        <div class="bg-yellow-900/20 border border-yellow-700/40 text-yellow-200 rounded-xl p-4 text-sm mb-4">
                            Спершу привʼяжіть нік у <a href="{{ route('profile.index') }}" class="underline">профілі</a>, інакше сервер не знатиме, чий це скін.
                        </div>
                    @endunless
                    <form method="POST" action="{{ route('profile.skin.upload') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">PNG 64×64 або 64×32, до {{ config('palermo.skins.max_kb') }} КБ</label>
                            <input type="file" name="skin" accept="image/png" required
                                   class="w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-gray-800 file:text-palermo-green">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Модель</label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2"><input type="radio" name="model" value="classic" checked> Steve (класична)</label>
                                <label class="flex items-center gap-2"><input type="radio" name="model" value="slim" @checked($skin?->model === 'slim')> Alex (тонкі руки)</label>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-palermo-green text-black font-bold py-3 rounded-xl hover:bg-green-500 transition-colors uppercase">Завантажити</button>
                    </form>
                    <p class="text-xs text-gray-500 mt-4">
                        Скін бачать усі гравці — і з ліцензійного, і з піратського лаунчера, без модів.
                        @if (config('palermo.skins.require_approval')) Перед появою в грі його перевіряє модератор. @endif
                    </p>
                </section>
            </div>
        </div>
    </main>
@endsection

@if ($skin)
    @push('javascripts')
        <script src="https://unpkg.com/skinview3d@3.1.0/bundles/skinview3d.bundle.js"></script>
        <script>
            const viewer = new skinview3d.SkinViewer({
                canvas: document.getElementById('skin-viewer'),
                width: 300, height: 400,
                skin: @json(route('skins.preview', $skin)) + '?v={{ $skin->hash }}',
                model: @json($skin->model === 'slim' ? 'slim' : 'default'),
            });
            viewer.animation = new skinview3d.WalkingAnimation();
            viewer.autoRotate = true;
        </script>
    @endpush
@endif
