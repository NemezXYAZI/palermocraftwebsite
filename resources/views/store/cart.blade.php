@extends('layouts.app')

@section('title', 'Корзина - PalermoCraft')

@section('content')
    <main class="flex-grow py-10 px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-palermo-green mb-2">Корзина</h1>
                    <p class="text-gray-400">Перевір товари перед оплатою.</p>
                </div>

                <div class="bg-palermo-card border border-palermo-green/30 rounded-2xl p-5 min-w-[260px]">
                    <p class="text-gray-400 text-sm mb-1">Ваш баланс</p>
                    <p class="text-3xl font-bold text-palermo-green">{{ number_format(auth()->user()->balance, 0, '.', ' ') }} монет</p>
                </div>
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

            @php
                $cartTotal = array_sum(array_column($cartItems, 'total'));
            @endphp

            @if (count($cartItems) > 0)
                <div class="space-y-4 mb-8">
                    @foreach ($cartItems as $item)
                        <div class="bg-palermo-card border border-gray-800 rounded-2xl p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-bold">{{ $item['name'] }}</h2>
                                <p class="text-gray-400 text-sm">{{ $item['description'] }}</p>
                                <p class="text-xs text-gray-500 mt-2">
                                    Сервер: {{ $item['server_name'] }} · Кількість: {{ $item['quantity'] }}
                                </p>
                            </div>

                            <div class="flex flex-col md:items-end gap-3">
                                <p class="text-palermo-green font-bold text-xl">
                                    {{ number_format($item['total'], 0, '.', ' ') }} монет
                                </p>
                                    @csrf
                                    <button type="submit" class="bg-red-900/50 text-red-300 border border-red-800 px-4 py-2 rounded-xl hover:bg-red-900 transition-colors text-sm font-bold">
                                        Видалити
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bg-palermo-card border border-palermo-green/30 rounded-3xl p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Загальна сума</p>
                            <p class="text-4xl font-bold text-palermo-green">
                                {{ number_format($cartTotal, 0, '.', ' ') }} монет
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('store') }}" class="text-center bg-gray-900 border border-gray-700 text-gray-200 font-bold px-6 py-3 rounded-xl hover:border-palermo-green hover:text-palermo-green transition-colors">
                                Продовжити покупки
                            </a>

                                @csrf
                                <button type="submit" class="w-full bg-palermo-green text-black font-bold px-6 py-3 rounded-xl hover:bg-green-500 transition-colors">
                                    Оплатити та видати
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-palermo-card border border-gray-800 rounded-3xl p-10 text-center">
                    <h2 class="text-2xl font-bold mb-3">Корзина порожня</h2>
                    <p class="text-gray-400 mb-6">Додай товари з магазину, щоб оформити покупку.</p>
                    <a href="{{ route('store') }}" class="inline-block bg-palermo-green text-black font-bold px-6 py-3 rounded-xl hover:bg-green-500 transition-colors">
                        Перейти в магазин
                    </a>
                </div>
            @endif
        </div>
    </main>
@endsection
