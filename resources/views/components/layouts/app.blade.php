<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $title ?? 'Demo' }} · Selection Process Demo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-full text-slate-800 antialiased">
    @php
        $nav = [
            ['dashboard', 'Dashboard', 'dashboard'],
            ['candidates.index', 'Postulantes', 'postulantes*'],
            ['processes.index', 'Procesos', 'procesos*'],
            ['ranking.index', 'Ranking', 'ranking*'],
        ];
    @endphp

    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center gap-x-6 gap-y-3 px-4 py-3">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-sm font-bold text-white">SP</span>
                <span class="leading-tight">
                    <span class="block text-base font-semibold text-slate-900">Selection Process Demo</span>
                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">DEMO · Datos ficticios</span>
                </span>
            </a>

            <nav class="order-last flex w-full gap-1 overflow-x-auto sm:order-none sm:w-auto" aria-label="Principal">
                @foreach ($nav as [$route, $label, $pattern])
                    <a href="{{ route($route) }}" wire:navigate
                       @class([
                           'whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium',
                           'bg-indigo-50 text-indigo-700' => request()->is($pattern),
                           'text-slate-600 hover:bg-slate-100' => ! request()->is($pattern),
                       ])>{{ $label }}</a>
                @endforeach
            </nav>

            <div class="ml-auto">
                <livewire:demo-reset-button />
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8">
        @if (session('status'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">{{ session('error') }}</div>
        @endif

        {{ $slot }}
    </main>

    <footer class="mx-auto max-w-6xl px-4 pb-10 text-center text-xs text-slate-500">
        Proyecto DEMO con datos completamente ficticios · Sin autenticación · Todos los derechos reservados
    </footer>

    @livewireScripts
</body>
</html>
