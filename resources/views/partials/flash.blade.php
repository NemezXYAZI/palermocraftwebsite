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
