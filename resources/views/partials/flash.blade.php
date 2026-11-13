@if (session('success') || $errors->any())
    <div class="flash-stack">
        @if (session('success'))
            <div class="alert alert--success" role="status">
                <x-icon name="circle-check" />
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert--error" role="alert">
                <x-icon name="circle-alert" />
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endif
