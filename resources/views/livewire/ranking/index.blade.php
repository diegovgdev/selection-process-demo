<div class="space-y-6">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Ranking</h1>
            <p class="mt-1 text-sm text-slate-600">Puntaje final ponderado: documental 30% · entrevista 30% · técnica 40%.</p>
        </div>
        <div>
            <label for="process" class="block text-xs font-medium text-slate-500">Proceso</label>
            <select id="process" wire:model.live="processId"
                    class="mt-1 w-64 rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @foreach ($processes as $process)
                    <option value="{{ $process->id }}" @selected($current && $current->id === $process->id)>{{ $process->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Postulante</th>
                    <th class="px-4 py-3 text-right">Documental</th>
                    <th class="px-4 py-3 text-right">Entrevista</th>
                    <th class="px-4 py-3 text-right">Técnica</th>
                    <th class="px-4 py-3 text-right">Final</th>
                    <th class="px-4 py-3">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($ranking as $row)
                    @php($a = $row['application'])
                    <tr wire:key="rank-{{ $a->id }}">
                        <td class="px-4 py-3 font-bold text-slate-400">{{ $row['position'] }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('candidates.show', $a) }}" wire:navigate class="font-medium text-indigo-700 hover:underline">{{ $a->candidate->name }}</a>
                            <p class="text-xs text-slate-500">{{ $a->candidate->code }}</p>
                        </td>
                        <td class="px-4 py-3 text-right">{{ $a->evaluation?->documents_score ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">{{ $a->evaluation?->interview_score ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">{{ $a->evaluation?->technical_score ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-base font-bold">{{ $row['final'] !== null ? number_format($row['final'], 1) : '—' }}</td>
                        <td class="px-4 py-3"><x-stage-badge :stage="$a->stage" /></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-slate-500">Este proceso aún no tiene postulaciones.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p class="text-xs text-slate-500">Los componentes sin evaluar se omiten y el resto de los pesos se reescala. Desempate: fecha de postulación más antigua.</p>
</div>
