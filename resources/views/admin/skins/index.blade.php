@extends('admin.layout')

@section('title', 'Скіни — адмін-панель')

@section('admin')
    <div class="admin__head">
        <h1>Скіни</h1>
        <nav class="pill-tabs" aria-label="Статус скінів">
            @foreach (['pending' => 'На перевірці', 'approved' => 'Схвалені', 'rejected' => 'Відхилені'] as $key => $label)
                <a href="{{ route('admin.skins.index', ['status' => $key]) }}" @if ($status === $key) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </nav>
    </div>

    @unless (app(\App\Services\MineSkinClient::class)->isConfigured())
        <div class="alert alert--warn" style="margin-bottom: 20px">
            <x-icon name="triangle-alert" />
            <p>MINESKIN_API_KEY не задано: схвалені скіни бачитимуть лише гравці з модом. Ключ — account.mineskin.org → API Keys.</p>
        </div>
    @endunless

    @if ($skins->isEmpty())
        <div class="empty">
            <x-icon name="shirt" />
            <h3>Тут порожньо</h3>
            <p class="mb-0">Нових скінів на цій вкладці немає.</p>
        </div>
    @else
        <div class="grid grid--3">
            @foreach ($skins as $skin)
                <article class="card skin-review">
                    <div class="skin-review__top">
                        <img class="skin-review__img" src="{{ route('skins.preview', $skin) }}?v={{ $skin->hash }}" alt="Скін {{ $skin->user->minecraft_name ?? $skin->user->name }}" width="112" height="112">
                        <div style="min-width: 0">
                            <a href="{{ route('admin.users.show', $skin->user) }}"><strong>{{ $skin->user->minecraft_name ?? $skin->user->name }}</strong></a>
                            <span class="table__sub">{{ $skin->model === 'slim' ? 'Alex' : 'Steve' }} · {{ $skin->updated_at->format('d.m H:i') }}</span>
                            @if ($skin->isApproved())
                                <span @class(['badge', 'mt-0', 'badge--success' => $skin->isSigned(), 'badge--warn' => ! $skin->isSigned()]) style="margin-top: 8px">
                                    {{ $skin->isSigned() ? 'Підписано' : 'Без підпису' }}
                                </span>
                                @if ($skin->sign_error)
                                    <span class="table__sub" style="color: #ffadad">{{ $skin->sign_error }}</span>
                                @endif
                            @endif
                            @if ($skin->rejection_reason)
                                <span class="table__sub" style="color: #ffadad">{{ $skin->rejection_reason }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="cluster cluster--sm">
                        @unless ($skin->isApproved())
                            <form method="POST" action="{{ route('admin.skins.approve', $skin) }}">
                                @csrf
                                <button class="btn btn--success btn--sm"><x-icon name="check" /> Схвалити</button>
                            </form>
                        @endunless
                        @if ($skin->isApproved() && ! $skin->isSigned())
                            <form method="POST" action="{{ route('admin.skins.resign', $skin) }}">
                                @csrf
                                <button class="btn btn--outline btn--sm">Підписати ще раз</button>
                            </form>
                        @endif
                    </div>
                    @if ($skin->status !== 'rejected')
                        <form method="POST" action="{{ route('admin.skins.reject', $skin) }}" class="input-group">
                            @csrf
                            <input name="reason" required maxlength="255" placeholder="Причина відмови" class="input" aria-label="Причина відмови" style="min-height: 36px; padding: .45rem .7rem; font-size: .9rem">
                            <button class="btn btn--danger btn--sm">Відхилити</button>
                        </form>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
    {{ $skins->links() }}
@endsection
