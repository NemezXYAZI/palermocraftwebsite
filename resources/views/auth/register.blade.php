@extends('layouts.app')

@section('title', 'Реєстрація - PalermoCraft')

@section('content')
    <main class="flex-grow py-16 px-4 max-w-lg mx-auto w-full">
        <div class="bg-palermo-card border border-gray-800 rounded-3xl p-8 shadow-2xl shadow-palermo-green/10">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-palermo-green mb-2">Реєстрація</h1>
                <p class="text-gray-400 text-sm">Створи акаунт PalermoCraft і почни гру</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-900/30 border border-red-700/50 text-red-300 rounded-xl p-4 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Нікнейм</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors"
                        placeholder="Nemez"
                    >
                </div>

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
                        placeholder="Мінімум 8 символів"
                    >
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Повторіть пароль</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full bg-gray-900 border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:border-palermo-green transition-colors"
                        placeholder="••••••••"
                    >
                </div>

                <button type="submit" class="w-full bg-palermo-green text-black font-bold py-3 rounded-xl hover:bg-green-500 transition-colors uppercase">
                    Створити акаунт
                </button>
            </form>

            <p class="text-center text-gray-400 text-sm mt-6">
                Вже маєш акаунт?
                <a href="{{ route('login') }}" class="text-palermo-green hover:text-white transition-colors font-bold">Увійти</a>
            </p>
        </div>
    </main>
@endsection
