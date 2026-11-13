@php
    $navItems = [
        ['route' => 'world', 'label' => 'Світ', 'icon' => 'map'],
        ['route' => 'guide', 'label' => 'Путівник', 'icon' => 'compass'],
        ['route' => 'news.index', 'label' => 'Новини', 'icon' => 'newspaper', 'match' => 'news.*'],
        ['route' => 'rules', 'label' => 'Правила', 'icon' => 'scroll-text'],
        ['route' => 'support', 'label' => 'Підтримка', 'icon' => 'heart-handshake'],
    ];
    $authUser = auth()->user();
    $authSkin = $authUser?->skin;
    $authAvatar = $authSkin ? route('skins.preview', $authSkin).'?v='.$authSkin->hash : null;
    $authName = $authUser ? ($authUser->minecraft_name ?: $authUser->name) : null;
@endphp
<header class="site-header" data-header>
    <div class="container site-header__inner">
        <a href="{{ route('home') }}" class="brand" aria-label="PALERMOCRAFT — на головну">
            @include('partials.mark')
            <span class="brand__name">PALERMOCRAFT</span>
        </a>

        <nav class="nav" aria-label="Головне меню">
            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}" class="nav__link"
                   @if (request()->routeIs($item['match'] ?? $item['route'])) aria-current="page" @endif>{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="site-header__actions">
            <x-status-pill />

            @auth
                <details class="dropdown" data-menu>
                    <summary class="user-chip" aria-label="Меню акаунта">
                        <x-head :name="$authName" :src="$authAvatar" size="30" round />
                        <span class="hide-mobile">{{ \Illuminate\Support\Str::limit($authName, 16) }}</span>
                        <x-icon name="chevron-down" />
                    </summary>
                    <div class="dropdown__panel">
                        <div class="dropdown__head">
                            <strong>{{ $authUser->name }}</strong>
                            <span>{{ $authUser->minecraft_name ? 'Нік у грі: '.$authUser->minecraft_name : 'Нік у грі ще не привʼязано' }}</span>
                        </div>
                        <a class="menu-link" href="{{ route('profile.index') }}"><x-icon name="user-round" /> Профіль</a>
                        <a class="menu-link" href="{{ route('profile.skin') }}"><x-icon name="shirt" /> Мій скін</a>
                        @if ($authUser->isStaff())
                            <a class="menu-link menu-link--gold" href="{{ route('admin.dashboard') }}"><x-icon name="shield" /> Адмін-панель</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="menu-link menu-link--danger" type="submit"><x-icon name="log-out" /> Вийти</button>
                        </form>
                    </div>
                </details>
            @else
                <a href="{{ route('login') }}" class="btn btn--ghost btn--sm hide-mobile">Увійти</a>
                <a href="{{ route('guide') }}#join" class="btn btn--primary btn--sm">Грати</a>
            @endauth

            <details class="nav-burger" data-menu>
                <summary aria-label="Меню">
                    <x-icon name="menu" class="icon-open" />
                    <x-icon name="x" class="icon-close" />
                </summary>
                <div class="nav-burger__panel">
                    <nav aria-label="Мобільне меню">
                        <a href="{{ route('home') }}" class="menu-link" @if (request()->routeIs('home')) aria-current="page" @endif><x-icon name="house" /> Головна</a>
                        @foreach ($navItems as $item)
                            <a href="{{ route($item['route']) }}" class="menu-link"
                               @if (request()->routeIs($item['match'] ?? $item['route'])) aria-current="page" @endif><x-icon :name="$item['icon']" /> {{ $item['label'] }}</a>
                        @endforeach
                    </nav>
                    <hr>
                    @auth
                        <a href="{{ route('profile.index') }}" class="menu-link"><x-icon name="user-round" /> Профіль</a>
                        <a href="{{ route('profile.skin') }}" class="menu-link"><x-icon name="shirt" /> Мій скін</a>
                    @else
                        <a href="{{ route('login') }}" class="menu-link"><x-icon name="log-in" /> Увійти</a>
                        <a href="{{ route('register') }}" class="menu-link menu-link--gold"><x-icon name="user-round" /> Створити акаунт</a>
                    @endauth
                </div>
            </details>
        </div>
    </div>
</header>
