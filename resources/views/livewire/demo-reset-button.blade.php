<div>
    <button type="button"
            wire:click="resetDemo"
            wire:confirm="¿Restaurar los datos ficticios iniciales? Se perderán los cambios realizados en la demo."
            wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 disabled:opacity-60">
        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.1A7 7 0 1117 10a1 1 0 11-2 0 5 5 0 10-4.9 5 1 1 0 010 2A7 7 0 014 7.9V9a1 1 0 11-2 0V3a1 1 0 011-1h1z" clip-rule="evenodd"/></svg>
        <span wire:loading.remove>Reiniciar demo</span>
        <span wire:loading>Reiniciando…</span>
    </button>
</div>
