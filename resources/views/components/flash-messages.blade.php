@props(['class' => ''])

@if(session('success') || session('error') || session('status') || $errors->any())
    <div {{ $attributes->merge(['class' => 'space-y-3 '.$class]) }}>
        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-800 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif

        @if(session('status'))
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm font-semibold text-blue-800 dark:border-blue-900 dark:bg-blue-950 dark:text-blue-200">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm font-semibold text-amber-900 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-100">
                Formularz zawiera bledy. Popraw oznaczone pola i sprobuj ponownie.
            </div>
        @endif
    </div>
@endif
