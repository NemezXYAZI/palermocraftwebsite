@extends('layouts.app')

@section('title', 'Магазин - PalermoCraft')

@section('content')
    <main class="flex-grow py-10 px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-palermo-green mb-2">Магазин</h1>
                    <p class="text-gray-400">Обери товар, сервер, кількість і спосіб отримання.</p>
                </div>

                @auth
                    <div class="bg-palermo-card border border-palermo-green/30 rounded-2xl p-5 min-w-[260px]">
                        <p class="text-gray-400 text-sm mb-1">Ваш баланс</p>
                        <p class="text-3xl font-bold text-palermo-green">{{ number_format(auth()->user()->balance, 0, '.', ' ') }} монет</p>
                        </a>
                    </div>
                @else
                    <div class="bg-palermo-card border border-gray-800 rounded-2xl p-5">
                        <p class="text-gray-400 text-sm mb-3">Для покупки потрібно увійти в акаунт.</p>
                        <a href="{{ route('login') }}" class="inline-block bg-palermo-green text-black font-bold px-5 py-3 rounded-xl hover:bg-green-500 transition-colors">
                            Увійти
                        </a>
                    </div>
                @endauth
            </div>

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

            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach ($products as $product)
                    <div class="bg-palermo-card border border-gray-800 rounded-3xl overflow-hidden shadow-lg hover:border-palermo-green/50 hover:shadow-palermo-green/10 transition-all">
                        <div class="relative h-48 bg-gray-900 overflow-hidden">
                            <img
                                src="{{ asset($product['image']) }}"
                                alt="{{ $product['name'] }}"
                                class="w-full h-full object-cover opacity-80 hover:scale-105 transition-transform duration-500"
                                onerror="this.src='https://via.placeholder.com/700x400/111111/39ff14?text=PalermoCraft'"
                            >

                            <div class="absolute top-4 left-4 bg-black/70 border border-palermo-green/40 text-palermo-green px-3 py-1 rounded-full text-xs font-bold uppercase">
                                {{ $product['type'] }}
                            </div>

                            <div class="absolute bottom-4 right-4 bg-palermo-green text-black px-4 py-2 rounded-xl font-bold">
                                {{ number_format($product['price'], 0, '.', ' ') }} монет
                            </div>
                        </div>

                        <div class="p-6">
                            <h2 class="text-xl font-bold mb-2">{{ $product['name'] }}</h2>
                            <p class="text-gray-400 text-sm min-h-[48px] mb-5">{{ $product['description'] }}</p>

                            <form method="POST" action="{{ route('store.buy-now') }}" class="space-y-4">
                                @csrf

                                <input type="hidden" name="product_id" value="{{ $product['id'] }}">

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Сервер</label>
                                    <select
                                        name="server"
                                        required
                                        class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors"
                                    >
                                        @foreach ($servers as $serverKey => $serverName)
                                            <option value="{{ $serverKey }}">{{ $serverName }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Кількість</label>
                                    <input
                                        type="number"
                                        name="quantity"
                                        value="1"
                                        min="1"
                                        max="99"
                                        required
                                        class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors"
                                    >
                                </div>

                                @auth
                                    <button type="submit" class="w-full bg-palermo-green text-black font-bold py-3 rounded-xl hover:bg-green-500 transition-colors">
                                        Купити та видати на сервер
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="block text-center w-full bg-palermo-green text-black font-bold py-3 rounded-xl hover:bg-green-500 transition-colors">
                                        Увійти для покупки
                                    </a>
                                @endauth
                            </form>

                            @auth
                                    @csrf

                                    <input type="hidden" name="product_id" value="{{ $product['id'] }}">

                                    <div class="grid grid-cols-2 gap-3 mb-3">
                                        <select
                                            name="server"
                                            required
                                            class="w-full bg-gray-900 border border-gray-700 text-white px-3 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors text-sm"
                                        >
                                            @foreach ($servers as $serverKey => $serverName)
                                                <option value="{{ $serverKey }}">{{ $serverName }}</option>
                                            @endforeach
                                        </select>

                                        <input
                                            type="number"
                                            name="quantity"
                                            value="1"
                                            min="1"
                                            max="99"
                                            required
                                            class="w-full bg-gray-900 border border-gray-700 text-white px-3 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors text-sm"
                                        >
                                    </div>

                                    <button type="submit" class="w-full bg-gray-900 border border-gray-700 text-gray-200 font-bold py-3 rounded-xl hover:border-palermo-green hover:text-palermo-green transition-colors">
                                        Додати в корзину
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
