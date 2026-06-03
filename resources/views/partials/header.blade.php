<header class="flex justify-between items-center py-4 px-8 border-b-2 border-neon bg-palermo-dark sticky top-0 z-40">
    <div class="text-xl font-bold uppercase tracking-wider flex items-center">
        <a href="{{ route('home') }}" class="hover:text-palermo-green transition-colors">PALERMOCRAFT</a>
    </div>

    <nav class="hidden md:block">
        <ul class="flex space-x-6 text-sm font-semibold">
            <li><a href="{{ route('home') }}" class="hover:text-palermo-green transition-colors">Головна</a></li>
            <li><a href="{{ route('about') }}" class="hover:text-palermo-green transition-colors">Про сервер</a></li>
            <li><a href="{{ route('rules') }}" class="hover:text-palermo-green transition-colors">Правила</a></li>
            <li><a href="{{ route('store') }}" class="hover:text-palermo-green transition-colors">Магазин</a></li>

            @auth
                <li><a href="{{ route('profile.index') }}" class="text-palermo-green hover:text-white transition-colors">Профіль</a></li>
            @else
                <li><a href="{{ route('login') }}" class="hover:text-palermo-green transition-colors">Вхід</a></li>
            @endauth
        </ul>
    </nav>

    @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-900/50 text-red-400 border border-red-900/50 font-bold py-2 px-4 rounded hover:bg-red-900 transition-colors text-sm uppercase">
                Вихід
            </button>
        </form>
    @else
        <a href="{{ route('register') }}" class="bg-palermo-green text-black font-bold py-2 px-4 rounded hover:bg-green-500 transition-colors text-sm uppercase">
            Реєстрація
        </a>
    @endauth
</header>
