@php
    $currentPosition = $application->stage->position();
    $rejected = $application->stage === \App\Enums\Stage::Rejected;
@endphp

<div class="space-y-6">
    <a href="{{ route('candidates.index') }}" wire:navigate class="text-sm text-indigo-600 hover:underline">← Volver a postulantes</a>

    @if (session('status'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">{{ session('status') }}</div>
    @endif

    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-amber-700">{{ $application->candidate->code }} · DEMO</p>
            <h1 class="text-2xl font-bold text-slate-900">{{ $application->candidate->name }}</h1>
            <p class="mt-1 text-slate-600">Postula a <span class="font-medium">{{ $application->process->name }}</span> · {{ $application->applied_at->format('d/m/Y') }}</p>
        </div>
        <x-stage-badge :stage="$application->stage" class="text-sm" />
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">Línea de tiempo</h2>
                <ol class="space-y-4">
                    @foreach ($pipeline as $index => $stage)
                        @php
                            $done = ! $rejected && $currentPosition !== null && $index < $currentPosition;
                            $current = ! $rejected && $index === $currentPosition;
                        @endphp
                        <li class="flex items-center gap-3">
                            <span @class([
                                'flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-bold',
                                'bg-emerald-500 text-white' => $done,
                                'bg-indigo-600 text-white ring-4 ring-indigo-100' => $current,
                                'bg-slate-100 text-slate-400' => ! $done && ! $current,
                            ])>{{ $done ? '✓' : $index + 1 }}</span>
                            <span @class(['text-sm', 'font-semibold text-slate-900' => $current, 'text-slate-600' => $done, 'text-slate-400' => ! $done && ! $current])>{{ $stage->label() }}</span>
                        </li>
                    @endforeach
                    @if ($rejected)
                        <li class="flex items-center gap-3">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-rose-600 text-xs font-bold text-white ring-4 ring-rose-100">✕</span>
                            <span class="text-sm font-semibold text-rose-700">No seleccionado</span>
                        </li>
                    @endif
                </ol>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">Resultados de evaluación</h2>
                <dl class="grid gap-4 sm:grid-cols-3">
                    @foreach ($scores as $label => $score)
                        <div class="rounded-lg bg-slate-50 p-4">
                            <dt class="text-xs text-slate-500">{{ $label }}</dt>
                            <dd class="mt-1 text-2xl font-bold {{ $score === null ? 'text-slate-300' : 'text-slate-900' }}">{{ $score ?? '—' }}</dd>
                            @if ($score === null)<p class="text-xs text-slate-400">Pendiente</p>@endif
                        </div>
                    @endforeach
                </dl>
                <div class="mt-4 flex items-center justify-between rounded-lg bg-indigo-50 p-4">
                    <div>
                        <p class="text-sm font-medium text-indigo-900">Puntaje consolidado</p>
                        <p class="text-xs text-indigo-700">Ponderación: documental 30% · entrevista 30% · técnica 40%</p>
                    </div>
                    <p class="text-3xl font-bold text-indigo-700">{{ $final !== null ? number_format($final, 1) : '—' }}</p>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Comentario de evaluación (ficticio)</h3>
                    <p class="mt-1 text-sm text-slate-600">{{ $application->comment ?? 'Sin comentarios.' }}</p>
                </div>
            </section>
        </div>

        <aside class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm lg:self-start">
            <h2 class="mb-1 text-lg font-semibold text-slate-900">Cambiar estado</h2>
            <p class="mb-4 text-sm text-slate-500">Mueve la postulación entre etapas disponibles.</p>
            <form wire:submit="changeStage" class="space-y-3">
                <label for="newStage" class="sr-only">Nueva etapa</label>
                <select id="newStage" wire:model="newStage"
                        class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach ($stages as $stage)
                        <option value="{{ $stage->value }}">{{ $stage->label() }}</option>
                    @endforeach
                </select>
                @error('newStage')<p class="text-sm text-rose-600" role="alert">{{ $message }}</p>@enderror
                <button type="submit" class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Actualizar estado</button>
            </form>
            <p class="mt-3 text-xs text-slate-400">Los cambios se pueden revertir con «Reiniciar demo».</p>
        </aside>
    </div>
</div>
