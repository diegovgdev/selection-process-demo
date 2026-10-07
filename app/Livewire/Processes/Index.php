<?php

namespace App\Livewire\Processes;

use App\Enums\Stage;
use App\Models\SelectionProcess;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Procesos')]
class Index extends Component
{
    public function render()
    {
        $processes = SelectionProcess::query()->with('applications')->orderBy('id')->get()->map(function (SelectionProcess $p) {
            $selected = $p->applications->where('stage', Stage::Selected)->count();

            return [
                'process' => $p,
                'applications' => $p->applications->count(),
                'selected' => $selected,
                'progress' => $p->slots > 0 ? min(100, (int) round($selected / $p->slots * 100)) : 0,
            ];
        });

        return view('livewire.processes.index', ['rows' => $processes]);
    }
}
