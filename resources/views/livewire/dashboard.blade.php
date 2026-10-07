<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-600">Resumen de los procesos de selección ficticios de esta demo.</p>
    </div>

    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4" aria-label="Indicadores">
        @foreach ([
            ['Total de postulaciones', $total, 'text-slate-900'],
            ['Postulaciones activas', $active, 'text-indigo-700'],
            ['Personas en evaluación', $evaluating, 'text-amber-700'],
            ['Personas seleccionadas', $selected, 'text-emerald-700'],
        ] as [$label, $value, $color])
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">{{ $label }}</p>
                <p class="mt-2 text-3xl font-bold {{ $color }}">{{ $value }}</p>
            </div>
        @endforeach
    </section>

    <section aria-label="Distribución por etapa">
        <h2 class="mb-3 text-lg font-semibold text-slate-900">Distribución por etapa</h2>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
            @foreach ($stages as $row)
                <a href="{{ route('candidates.index', ['estado' => $row['stage']->value]) }}" wire:navigate
                   class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-indigo-300">
                    <p class="text-xs font-medium text-slate-500">{{ $row['stage']->label() }}</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $row['count'] }}</p>
                    <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full {{ $row['stage']->barColor() }}" style="width: {{ $row['percent'] }}%"></div>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">{{ $row['percent'] }}%</p>
                </a>
            @endforeach
        </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="lg:col-span-2" aria-label="Actividad reciente">
            <h2 class="mb-3 text-lg font-semibold text-slate-900">Actividad reciente</h2>
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                        <tr><th class="px-4 py-2">Fecha</th><th class="px-4 py-2">Evento</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($activity as $log)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 text-slate-500">{{ $log->occurred_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-2">{{ $log->message }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-4 py-6 text-center text-slate-500">Sin actividad registrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section aria-label="Accesos rápidos">
            <h2 class="mb-3 text-lg font-semibold text-slate-900">Accesos</h2>
            <div class="space-y-3">
                @foreach ([
                    ['candidates.index', 'Postulantes', 'Listado, búsqueda y fichas individuales'],
                    ['processes.index', 'Procesos', 'Cupos, postulaciones y avance'],
                    ['ranking.index', 'Ranking', 'Puntajes consolidados por proceso'],
                ] as [$route, $title, $text])
                    <a href="{{ route($route) }}" wire:navigate
                       class="block rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-indigo-300">
                        <p class="font-semibold text-indigo-700">{{ $title }} →</p>
                        <p class="text-sm text-slate-500">{{ $text }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</div>
