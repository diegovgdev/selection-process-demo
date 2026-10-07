<?php

namespace App\Livewire\Ranking;

use App\Models\Application;
use App\Models\SelectionProcess;
use App\Services\RankingCalculator;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Ranking')]
class Index extends Component
{
    #[Url(as: 'proceso', except: '')]
    public ?int $processId = null;

    public function render(RankingCalculator $calculator)
    {
        $processes = SelectionProcess::query()->orderBy('id')->get();
        $current = $processes->firstWhere('id', $this->processId) ?? $processes->first();

        $ranking = collect();
        if ($current) {
            $applications = Application::query()
                ->with(['candidate', 'evaluation'])
                ->where('selection_process_id', $current->id)
                ->get();
            $ranking = $calculator->rank($applications);
        }

        return view('livewire.ranking.index', [
            'processes' => $processes,
            'current' => $current,
            'ranking' => $ranking,
        ]);
    }
}
