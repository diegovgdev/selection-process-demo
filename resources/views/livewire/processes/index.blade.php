<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Procesos</h1>
        <p class="mt-1 text-sm text-slate-600">Procesos de selección ficticios y su nivel de avance.</p>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        @forelse ($rows as $row)
            <article class="flex flex-col rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <h2 class="text-lg font-semibold text-slate-900">{{ $row['process']->name }}</h2>
                    <span @class([
                        'rounded-full px-2 py-0.5 text-xs font-medium',
                        'bg-emerald-50 text-emerald-700' => $row['process']->status === 'open',
                        'bg-slate-100 text-slate-600' => $row['process']->status !== 'open',
                    ])>{{ $row['process']->statusLabel() }}</span>
                </div>
                <p class="mt-2 flex-1 text-sm text-slate-500">{{ $row['process']->description }}</p>

                <dl class="mt-4 grid grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-slate-500">Cupos</dt><dd class="text-xl font-bold">{{ $row['process']->slots }}</dd></div>
                    <div><dt class="text-slate-500">Postulaciones</dt><dd class="text-xl font-bold">{{ $row['applications'] }}</dd></div>
                </dl>

                <div class="mt-4">
                    <div class="mb-1 flex justify-between text-xs text-slate-500">
                        <span>Cupos cubiertos</span><span>{{ $row['selected'] }} / {{ $row['process']->slots }}</span>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-slate-100" role="progressbar" aria-valuenow="{{ $row['progress'] }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="h-full rounded-full bg-indigo-500" style="width: {{ $row['progress'] }}%"></div>
                    </div>
                </div>

                <a href="{{ route('ranking.index', ['proceso' => $row['process']->id]) }}" wire:navigate
                   class="mt-4 text-sm font-medium text-indigo-600 hover:text-indigo-800">Ver ranking →</a>
            </article>
        @empty
            <p class="col-span-full rounded-xl border border-dashed border-slate-300 p-10 text-center text-slate-500">No hay procesos. Usa «Reiniciar demo» para restaurar los datos.</p>
        @endforelse
    </div>
</div>
