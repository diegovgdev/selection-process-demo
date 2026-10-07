<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Postulantes</h1>
        <p class="mt-1 text-sm text-slate-600">Personas ficticias que postulan a los procesos de la demo.</p>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row">
        <div class="flex-1">
            <label for="search" class="sr-only">Buscar</label>
            <input id="search" type="search" wire:model.live.debounce.250ms="search"
                   placeholder="Buscar por nombre, código o cargo…"
                   class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label for="status" class="sr-only">Estado</label>
            <select id="status" wire:model.live="status"
                    class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-56">
                <option value="">Todos los estados</option>
                @foreach ($stages as $stage)
                    <option value="{{ $stage->value }}">{{ $stage->label() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Postulante</th>
                    <th class="px-4 py-3">Cargo</th>
                    <th class="px-4 py-3">Etapa</th>
                    <th class="px-4 py-3 text-right">Puntaje</th>
                    <th class="px-4 py-3">Postulación</th>
                    <th class="px-4 py-3"><span class="sr-only">Acciones</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($applications as $application)
                    <tr wire:key="app-{{ $application->id }}" class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-slate-900">{{ $application->candidate->name }}</p>
                            <p class="text-xs text-slate-500">{{ $application->candidate->code }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $application->process->name }}</td>
                        <td class="px-4 py-3"><x-stage-badge :stage="$application->stage" /></td>
                        <td class="px-4 py-3 text-right font-semibold">{{ $application->finalScore() !== null ? number_format($application->finalScore(), 1) : '—' }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-slate-500">{{ $application->applied_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('candidates.show', $application) }}" wire:navigate
                               class="font-medium text-indigo-600 hover:text-indigo-800">Ver detalle</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-500">
                            No se encontraron postulantes con esos criterios.
                            <button type="button" wire:click="clearFilters" class="ml-1 font-medium text-indigo-600 hover:underline">Limpiar filtros</button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p class="text-xs text-slate-500">{{ $applications->count() }} resultado(s). Todos los datos son ficticios.</p>
</div>
