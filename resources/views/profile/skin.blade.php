@extends('layouts.app')

@section('title', __('Мій скін'))
@section('noindex', true)

@php
    $status = match ($skin?->status) {
        'approved' => ['badge--success', 'circle-check', __('Схвалено')],
        'pending' => ['badge--warn', 'clock', __('На перевірці')],
        'rejected' => ['badge--danger', 'circle-x', __('Відхилено')],
        default => ['badge--violet', 'sparkles', __('Стандартний вигляд')],
    };
    $maxKb = (int) config('palermo.skins.max_kb');
@endphp

@section('content')
    <section class="section section--tight">
        <div class="container">
            <a class="back-link" href="{{ route('profile.index') }}"><x-icon name="arrow-left" /> {{ __('До профілю') }}</a>
            <h1 class="mt-lg" style="margin-top: 14px">{{ __('Мій скін') }}</h1>
            <p class="lead">{{ __('Скін бачать усі гравці — з ліцензійних і піратських лаунчерів, без модів.') }}</p>

            @include('partials.flash')

            <div class="skin-layout mt-lg">
                <div class="stack">
                    <div class="skin-stage" data-skin-stage>
                        <span class="skin-stage__label badge {{ $status[0] }}" data-skin-badge>
                            <x-icon :name="$status[1]" /> <span data-skin-badge-text>{{ $status[2] }}</span>
                        </span>
                        <canvas id="skin-viewer" aria-label="{{ __('Тривимірний перегляд скіна. Перетягни, щоб повернути.') }}"></canvas>
                        <div class="skin-stage__tools">
                            <button type="button" class="btn btn--ghost btn--sm btn--icon" data-skin-toggle aria-label="{{ __('Зупинити анімацію') }}" aria-pressed="false">
                                <x-icon name="pause" class="icon-pause" />
                                <x-icon name="play" class="icon-play" />
                            </button>
                        </div>
                        <noscript><p class="muted">{{ __('Для 3D-перегляду увімкни JavaScript.') }}</p></noscript>
                    </div>

                    @if ($skin)
                        <div class="card card--flat">
                            <dl class="kv">
                                <dt>{{ __('Модель') }}</dt>
                                <dd>{{ $skin->model === 'slim' ? __('Alex — тонкі руки') : __('Steve — класична') }}</dd>
                                <dt>{{ __('Оновлено') }}</dt>
                                <dd>{{ $skin->updated_at->translatedFormat('j F Y, H:i') }}</dd>
                                @if ($skin->status === 'rejected' && $skin->rejection_reason)
                                    <dt>{{ __('Причина') }}</dt>
                                    <dd>{{ $skin->rejection_reason }}</dd>
                                @endif
                            </dl>
                            @if ($skin->isApproved() && ! $skin->isSigned())
                                <div class="alert alert--info mt-lg">
                                    <x-icon name="info" />
                                    <p>{{ __('Скін схвалено, але ще не підписано — поки його бачать лише гравці з модом Palermo Skin Sync.') }} <a href="{{ route('mods') }}#ours">{{ __('Завантажити мод') }}</a>. {{ __('Підпис зʼявиться автоматично.') }}</p>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('profile.skin.destroy') }}" class="mt-lg">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn--danger btn--sm" data-confirm="{{ __('Точно видалити скін?') }}"><x-icon name="trash-2" /> {{ __('Видалити скін') }}</button>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="card">
                    <h2 class="panel-title"><x-icon name="upload" /> {{ __('Новий скін') }}</h2>

                    @unless ($user->minecraft_name)
                        <div class="alert alert--warn mt-lg">
                            <x-icon name="triangle-alert" />
                            <p>{{ __('Спершу') }} <a href="{{ route('profile.index') }}#game">{{ __('привʼяжи нік') }}</a> {{ __('у профілі — інакше сервер не знатиме, чий це скін.') }}</p>
                        </div>
                    @endunless

                    <form method="POST" action="{{ route('profile.skin.upload') }}" enctype="multipart/form-data" class="form mt-lg" data-skin-form>
                        @csrf
                        <label class="dropzone" data-dropzone>
                            <x-icon name="image" />
                            <strong>{{ __('Перетягни PNG сюди або натисни, щоб вибрати') }}</strong>
                            <span class="help">{{ __('64×64 або 64×32 пікселі, до :kb КБ', ['kb' => $maxKb]) }}</span>
                            <span class="dropzone__file" data-dropzone-file></span>
                            <input type="file" name="skin" accept="image/png" required data-skin-file>
                        </label>
                        <p class="field-error" data-skin-error hidden></p>

                        <fieldset class="field" style="border: 0; padding: 0; margin: 0">
                            <legend class="label" style="margin-bottom: 8px">{{ __('Модель') }}</legend>
                            <div class="choice-grid">
                                <label class="choice">
                                    <input type="radio" name="model" value="classic" @checked(old('model', $skin?->model ?? 'classic') !== 'slim')>
                                    <span class="choice__box"><strong>Steve</strong><span>{{ __('Класичні руки, 4 пікселі') }}</span></span>
                                </label>
                                <label class="choice">
                                    <input type="radio" name="model" value="slim" @checked(old('model', $skin?->model) === 'slim')>
                                    <span class="choice__box"><strong>Alex</strong><span>{{ __('Тонкі руки, 3 пікселі') }}</span></span>
                                </label>
                            </div>
                        </fieldset>

                        <button type="submit" class="btn btn--primary btn--lg btn--block"><x-icon name="upload" /> {{ __('Завантажити') }}</button>
                    </form>

                    <ul class="check-list mt-lg">
                        <li><x-icon name="check" /> {{ __('Обраний файл одразу видно в 3D-перегляді') }}</li>
                        <li><x-icon name="check" /> {{ __('Працює для всіх лаунчерів, без модів') }}</li>
                        @if (config('palermo.skins.require_approval'))
                            <li><x-icon name="clock" class="is-no" /> {{ __('Перед появою в грі скін перевіряє модератор') }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ \App\Support\Assets::url('vendor/skinview3d/skinview3d.bundle.js') }}"></script>
    <script>
        (function () {
            var canvas = document.getElementById('skin-viewer');
            var stage = document.querySelector('[data-skin-stage]');
            if (!canvas || !stage || !window.skinview3d) return;

            {{-- Js::from, а не @json: @json ріже вираз по комах --}}
            var current = {{ \Illuminate\Support\Js::from($skin ? route('skins.preview', $skin).'?v='.$skin->hash : null) }};
            var currentModel = {{ \Illuminate\Support\Js::from($skin?->model === 'slim' ? 'slim' : 'default') }};
            var maxBytes = {{ $maxKb }} * 1024;
            var t = {{ \Illuminate\Support\Js::from([
                'play' => __('Запустити анімацію'),
                'pause' => __('Зупинити анімацію'),
                'png' => __('Потрібен файл PNG.'),
                'tooBig' => __('Файл завеликий: до :kb КБ.', ['kb' => $maxKb]),
                'badSize' => __('Розмір скіна має бути 64×64 або 64×32 пікселі, а цей — :size.'),
                'preview' => __('Попередній перегляд'),
                'unreadable' => __('Не вдалося прочитати зображення.'),
            ]) }};

            // Мандрівник у каптурі — замість стандартного скіна, поки свого немає
            function wanderer() {
                var c = document.createElement('canvas');
                c.width = 64; c.height = 64;
                var g = c.getContext('2d');
                function f(x, y, w, h, col) { g.fillStyle = col; g.fillRect(x, y, w, h); }
                f(0, 0, 32, 16, '#1a1426');
                f(8, 8, 8, 8, '#0a0711');
                f(10, 12, 1, 1, '#c7b3ff'); f(13, 12, 1, 1, '#c7b3ff');
                f(16, 16, 24, 16, '#231b33');
                f(20, 20, 8, 12, '#2a2140');
                f(23, 20, 2, 12, '#c99a4a');
                f(40, 16, 16, 16, '#1d1729');
                f(32, 48, 16, 16, '#1d1729');
                f(0, 16, 16, 16, '#141020');
                f(16, 48, 16, 16, '#141020');
                f(32, 0, 32, 16, '#2c2240');
                g.clearRect(40, 8, 8, 8);
                f(40, 8, 8, 2, '#2c2240');
                f(40, 10, 1, 6, '#2c2240'); f(47, 10, 1, 6, '#2c2240');
                return c;
            }

            var viewer = new skinview3d.SkinViewer({ canvas: canvas, width: 320, height: 420 });
            viewer.zoom = 0.86;
            viewer.fov = 48;
            viewer.autoRotate = true;
            viewer.autoRotateSpeed = 0.55;
            viewer.animation = new skinview3d.WalkingAnimation();
            viewer.animation.speed = 0.6;
            viewer.loadSkin(current || wanderer(), { model: currentModel });

            function resize() {
                var w = Math.max(220, Math.min(stage.clientWidth - 24, 360));
                viewer.setSize(w, Math.round(w * 1.3));
            }
            resize();
            window.addEventListener('resize', resize);

            var toggle = document.querySelector('[data-skin-toggle]');
            if (toggle) {
                toggle.addEventListener('click', function () {
                    var paused = !viewer.animation.paused;
                    viewer.animation.paused = paused;
                    viewer.autoRotate = !paused;
                    toggle.setAttribute('aria-pressed', paused ? 'true' : 'false');
                    toggle.setAttribute('aria-label', paused ? t.play : t.pause);
                    toggle.classList.toggle('is-paused', paused);
                });
            }

            var form = document.querySelector('[data-skin-form]');
            var fileInput = document.querySelector('[data-skin-file]');
            var errorBox = document.querySelector('[data-skin-error]');
            var badgeText = document.querySelector('[data-skin-badge-text]');
            var preview = null;

            function model() {
                var checked = form.querySelector('input[name="model"]:checked');
                return checked && checked.value === 'slim' ? 'slim' : 'default';
            }
            function showError(text) {
                errorBox.textContent = text;
                errorBox.hidden = !text;
            }

            form.querySelectorAll('input[name="model"]').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    viewer.loadSkin(preview || current || wanderer(), { model: model() });
                });
            });

            fileInput.addEventListener('change', function () {
                showError('');
                var file = fileInput.files && fileInput.files[0];
                if (!file) return;
                if (file.type && file.type !== 'image/png') {
                    showError(t.png);
                    fileInput.value = '';
                    return;
                }
                if (file.size > maxBytes) {
                    showError(t.tooBig);
                    fileInput.value = '';
                    return;
                }
                var img = new Image();
                img.onload = function () {
                    if (img.width !== 64 || (img.height !== 64 && img.height !== 32)) {
                        showError(t.badSize.replace(':size', img.width + '×' + img.height));
                        fileInput.value = '';
                        return;
                    }
                    preview = img;
                    viewer.loadSkin(img, { model: model() });
                    if (badgeText) badgeText.textContent = t.preview;
                };
                img.onerror = function () { showError(t.unreadable); };
                img.src = URL.createObjectURL(file);
            });
        })();
    </script>
@endpush
