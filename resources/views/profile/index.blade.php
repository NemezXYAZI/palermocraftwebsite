@extends('layouts.app')

@section('title', 'Особистий кабінет - PalermoCraft')

@section('content')
    <main class="flex-grow py-10 px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-palermo-green mb-8 text-center">Особистий кабінет</h2>

            @if (session('success'))
                <div class="mb-6 bg-palermo-green/10 border border-palermo-green/40 text-palermo-green rounded-xl p-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-900/30 border border-red-700/50 text-red-300 rounded-xl p-4 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-8">
                <aside class="lg:w-1/4 bg-palermo-card p-4 rounded-2xl flex flex-col gap-2 h-fit border border-gray-800">
                    <button onclick="switchProfileTab('info')" id="tab-info" class="text-left px-4 py-3 rounded-xl bg-gray-800 text-palermo-green font-bold transition-colors">
                        Основна інформація
                    </button>

                    <button onclick="switchProfileTab('balance')" id="tab-balance" class="text-left px-4 py-3 rounded-xl hover:bg-gray-800 transition-colors text-gray-300">
                        Поповнення балансу
                    </button>

                    <button onclick="switchProfileTab('inventory')" id="tab-inventory" class="text-left px-4 py-3 rounded-xl hover:bg-gray-800 transition-colors text-gray-300">
                        Інвентар
                    </button>

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 rounded-xl hover:bg-red-900/50 text-red-500 transition-colors border border-red-900/30">
                            Вихід
                        </button>
                    </form>
                </aside>

                <section class="lg:w-3/4 bg-palermo-card p-6 md:p-8 rounded-2xl border border-gray-800 min-h-[500px]">

                    <div id="content-info" class="block">
                        <h3 class="text-xl font-bold mb-6 border-b border-gray-700 pb-2">Основна інформація</h3>

                        <div class="grid md:grid-cols-3 gap-4 mb-8">
                            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                                <p class="text-gray-400 text-sm mb-1">Нікнейм</p>
                                <p class="text-xl font-bold">{{ $user->name }}</p>
                            </div>

                            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                                <p class="text-gray-400 text-sm mb-1">Email</p>
                                <p class="text-sm font-bold break-all">{{ $user->email }}</p>
                            </div>

                            <div class="bg-gray-900 border border-palermo-green/40 rounded-2xl p-5 shadow-lg shadow-palermo-green/10">
                                <p class="text-gray-400 text-sm mb-1">Баланс</p>
                                <p class="text-3xl font-bold text-palermo-green">{{ number_format($user->balance, 0, '.', ' ') }} монет</p>
                            </div>
                        </div>

                        <div class="grid xl:grid-cols-2 gap-6">
                            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
                                <h4 class="font-bold text-palermo-green mb-4">Змінити логін</h4>

                                <form method="POST" action="{{ route('profile.username.update') }}" class="space-y-4">
                                    @csrf

                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Новий логін</label>
                                        <input
                                            type="text"
                                            name="name"
                                            value="{{ old('name', $user->name) }}"
                                            required
                                            class="w-full bg-palermo-dark border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors"
                                        >
                                    </div>

                                    <button type="submit" class="bg-palermo-green text-black font-bold px-5 py-3 rounded-xl hover:bg-green-500 transition-colors">
                                        Зберегти логін
                                    </button>
                                </form>
                            </div>

                            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
                                <h4 class="font-bold text-palermo-green mb-4">Змінити пароль</h4>

                                <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4">
                                    @csrf

                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Поточний пароль</label>
                                        <input
                                            type="password"
                                            name="current_password"
                                            required
                                            class="w-full bg-palermo-dark border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors"
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Новий пароль</label>
                                        <input
                                            type="password"
                                            name="password"
                                            required
                                            class="w-full bg-palermo-dark border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors"
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Повторіть новий пароль</label>
                                        <input
                                            type="password"
                                            name="password_confirmation"
                                            required
                                            class="w-full bg-palermo-dark border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors"
                                        >
                                    </div>

                                    <button type="submit" class="bg-palermo-green text-black font-bold px-5 py-3 rounded-xl hover:bg-green-500 transition-colors">
                                        Оновити пароль
                                    </button>
                                </form>
                            </div>

                            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
                                <h4 class="font-bold text-palermo-green mb-4">Підтвердження Email</h4>

                                @if ($user->email_verified_at)
                                    <div class="bg-palermo-green/10 border border-palermo-green/30 rounded-xl p-4">
                                        <p class="text-palermo-green font-bold">Email підтверджено</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            Дата підтвердження: {{ $user->email_verified_at->format('d.m.Y H:i') }}
                                        </p>
                                    </div>
                                @else
                                    <div class="bg-red-900/20 border border-red-800/50 rounded-xl p-4 mb-4">
                                        <p class="text-red-300 font-bold">Email не підтверджено</p>
                                        <p class="text-xs text-gray-400 mt-1">Натисніть кнопку нижче, щоб підтвердити email.</p>
                                    </div>

                                    <form method="POST" action="{{ route('profile.email.verify') }}">
                                        @csrf
                                        <button type="submit" class="bg-palermo-green text-black font-bold px-5 py-3 rounded-xl hover:bg-green-500 transition-colors">
                                            Підтвердити Email
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
                                <h4 class="font-bold text-palermo-green mb-4">Discord</h4>

                                @if ($user->discord_linked_at)
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div>
                                            <p class="font-bold">{{ $user->discord_username }}</p>
                                            <p class="text-xs text-palermo-green">
                                                Привʼязано: {{ $user->discord_linked_at->format('d.m.Y H:i') }}
                                            </p>
                                        </div>

                                        <form method="POST" action="{{ route('profile.discord.unlink') }}">
                                            @csrf
                                            <button type="submit" class="bg-red-900/50 text-red-300 border border-red-800 px-4 py-2 rounded-xl hover:bg-red-900 transition-colors text-sm font-bold">
                                                Відвʼязати
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('profile.discord.link') }}" class="space-y-4">
                                        @csrf

                                        <p class="text-sm text-gray-400">
                                            Введіть 6-значний код, отриманий від Discord-бота.
                                        </p>

                                        <input
                                            type="text"
                                            name="discord_code"
                                            maxlength="6"
                                            required
                                            placeholder="ABC123"
                                            class="w-full bg-palermo-dark border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors uppercase"
                                        >

                                        <button type="submit" class="bg-[#5865F2] hover:bg-[#4752C4] text-white font-bold px-5 py-3 rounded-xl transition-colors">
                                            Привʼязати Discord
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 xl:col-span-2">
                                <h4 class="font-bold text-palermo-green mb-4">Telegram</h4>

                                @if ($user->telegram_linked_at)
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div>
                                            <p class="font-bold">{{ $user->telegram_username }}</p>
                                            <p class="text-xs text-palermo-green">
                                                Привʼязано: {{ $user->telegram_linked_at->format('d.m.Y H:i') }}
                                            </p>
                                        </div>

                                        <form method="POST" action="{{ route('profile.telegram.unlink') }}">
                                            @csrf
                                            <button type="submit" class="bg-red-900/50 text-red-300 border border-red-800 px-4 py-2 rounded-xl hover:bg-red-900 transition-colors text-sm font-bold">
                                                Відвʼязати
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('profile.telegram.link') }}" class="space-y-4">
                                        @csrf

                                        <p class="text-sm text-gray-400">
                                            Введіть 6-значний код, отриманий від Telegram-бота.
                                        </p>

                                        <input
                                            type="text"
                                            name="telegram_code"
                                            maxlength="6"
                                            required
                                            placeholder="ABC123"
                                            class="w-full bg-palermo-dark border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors uppercase"
                                        >

                                        <button type="submit" class="bg-[#229ED9] hover:bg-[#1B89C2] text-white font-bold px-5 py-3 rounded-xl transition-colors">
                                            Привʼязати Telegram
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div id="content-balance" class="hidden">
                        <h3 class="text-xl font-bold mb-6 border-b border-gray-700 pb-2">Поповнення балансу</h3>

                        <div class="bg-gray-900 border border-palermo-green/30 rounded-2xl p-6 mb-6">
                            <p class="text-gray-400 text-sm mb-1">Ваш баланс</p>
                            <p class="text-4xl font-bold text-palermo-green">{{ number_format($user->balance, 0, '.', ' ') }} монет</p>
                        </div>

                        <form method="POST" action="{{ route('profile.top-up') }}" class="space-y-5">
                            @csrf

                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Сума поповнення</label>
                                <input
                                    type="number"
                                    name="amount"
                                    min="10"
                                    max="100000"
                                    required
                                    class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors"
                                    placeholder="Наприклад: 500"
                                >
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <button type="button" onclick="setAmount(100)" class="bg-gray-900 border border-gray-700 rounded-xl py-3 hover:border-palermo-green transition-colors">100</button>
                                <button type="button" onclick="setAmount(500)" class="bg-gray-900 border border-gray-700 rounded-xl py-3 hover:border-palermo-green transition-colors">500</button>
                                <button type="button" onclick="setAmount(1000)" class="bg-gray-900 border border-gray-700 rounded-xl py-3 hover:border-palermo-green transition-colors">1000</button>
                            </div>

                            <button type="submit" class="w-full bg-palermo-green text-black font-bold py-3 rounded-xl hover:bg-green-500 transition-colors uppercase">
                                Поповнити баланс
                            </button>
                        </form>
                    </div>

                    <div id="content-inventory" class="hidden">
                        <h3 class="text-xl font-bold mb-6 border-b border-gray-700 pb-2">Придбані предмети</h3>

                        @forelse ($inventoryItems as $item)
                            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div>
                                    <p class="font-bold text-lg">{{ $item->name }}</p>
                                    <p class="text-sm text-gray-400">{{ $item->description ?: 'Предмет PalermoCraft' }}</p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        Куплено:
                                        {{ optional($item->purchased_at)->format('d.m.Y H:i') ?? $item->created_at->format('d.m.Y H:i') }}
                                    </p>
                                </div>

                                <div class="flex flex-col md:items-end gap-2">
                                    <span class="text-palermo-green font-bold">{{ number_format($item->price, 0, '.', ' ') }} монет</span>

                                    @if ($item->status === 'active')
                                        <span class="text-yellow-300 text-sm font-bold">В інвентарі</span>

                                        <form method="POST" action="{{ route('profile.inventory.activate', $item) }}">
                                            @csrf
                                            <button type="submit" class="bg-palermo-green text-black px-4 py-2 rounded-xl hover:bg-green-500 transition-colors text-sm font-bold">
                                                Активувати на сервері
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('profile.inventory.refund', $item) }}">
                                            @csrf
                                            <button type="submit" class="bg-red-900/50 text-red-300 border border-red-800 px-4 py-2 rounded-xl hover:bg-red-900 transition-colors text-sm font-bold">
                                                Повернути / Refund
                                            </button>
                                        </form>
                                    @elseif ($item->status === 'activated')
                                        <span class="text-palermo-green text-sm font-bold">Активовано на сервері</span>
                                    @else
                                        <span class="text-gray-500 text-sm font-bold">Повернено</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-10 text-center">
                                <h4 class="text-xl font-bold mb-2">Інвентар порожній</h4>
                                <p class="text-gray-400 mb-6">Після покупок у магазині предмети зʼявляться тут.</p>
                                <a href="{{ route('store') }}" class="inline-block bg-palermo-green text-black font-bold px-6 py-3 rounded-xl hover:bg-green-500 transition-colors">
                                    Перейти в магазин
                                </a>
                            </div>
                        @endforelse
                    </div>

                </section>
            </div>
        </div>
    </main>
@endsection

@push('javascripts')
    <script>
        function switchProfileTab(tabId) {
            ['info', 'balance', 'inventory'].forEach(id => {
                document.getElementById('content-' + id).classList.add('hidden');
                document.getElementById('content-' + id).classList.remove('block');

                const btn = document.getElementById('tab-' + id);
                btn.classList.remove('bg-gray-800', 'text-palermo-green', 'font-bold');
                btn.classList.add('text-gray-300');
            });

            document.getElementById('content-' + tabId).classList.remove('hidden');
            document.getElementById('content-' + tabId).classList.add('block');

            const activeBtn = document.getElementById('tab-' + tabId);
            activeBtn.classList.remove('text-gray-300');
            activeBtn.classList.add('bg-gray-800', 'text-palermo-green', 'font-bold');
        }

        function setAmount(amount) {
            const input = document.querySelector('input[name="amount"]');

            if (input) {
                input.value = amount;
            }
        }
    </script>
@endpush
