@extends('layouts.app')

@section('title', 'Вхід - PalermoCraft')

@section('content')
    <main class="flex-grow py-16 px-4 max-w-lg mx-auto w-full">
        <div class="bg-palermo-card border border-gray-800 rounded-3xl p-8 shadow-2xl shadow-palermo-green/10">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-palermo-green mb-2">Вхід</h1>
                <p class="text-gray-400 text-sm">Повернись до свого PalermoCraft акаунта</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-900/30 border border-red-700/50 text-red-300 rounded-xl p-4 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors"
                        placeholder="player@palermo.ua"
                    >
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Пароль</label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors"
                        placeholder="••••••••"
                    >
                </div>

                <label class="flex items-center gap-3 text-sm text-gray-400">
                    <input type="checkbox" name="remember" class="accent-palermo-green">
                    Запамʼятати мене
                </label>

                <button type="submit" class="w-full bg-palermo-green text-black font-bold py-3 rounded-xl hover:bg-green-500 transition-colors uppercase">
                    Увійти
                </button>
            </form>

            <p class="text-center text-gray-400 text-sm mt-6">
                Немає акаунта?
                <a href="{{ route('register') }}" class="text-palermo-green hover:text-white transition-colors font-bold">Зареєструватися</a>
            </p>
        </div>
    </main>
@endsection
