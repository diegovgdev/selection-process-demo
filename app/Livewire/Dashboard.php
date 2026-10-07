<?php

namespace App\Livewire;

use App\Enums\Stage;
use App\Models\ActivityLog;
use App\Models\Application;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $byStage = Application::query()
            ->selectRaw('stage, count(*) as total')
            ->groupBy('stage')
            ->pluck('total', 'stage');

        $total = $byStage->sum();

        $stages = collect(Stage::cases())->map(fn (Stage $stage) => [
            'stage' => $stage,
            'count' => (int) ($byStage[$stage->value] ?? 0),
            'percent' => $total > 0 ? round(($byStage[$stage->value] ?? 0) / $total * 100) : 0,
        ]);

        $evaluating = [Stage::DocumentReview, Stage::Interview, Stage::Technical];

        return view('livewire.dashboard', [
            'total' => $total,
            'active' => collect(Stage::cases())->filter->isActive()->sum(fn (Stage $s) => (int) ($byStage[$s->value] ?? 0)),
            'evaluating' => collect($evaluating)->sum(fn (Stage $s) => (int) ($byStage[$s->value] ?? 0)),
            'selected' => (int) ($byStage[Stage::Selected->value] ?? 0),
            'stages' => $stages,
            'activity' => ActivityLog::query()->with('application')->latest('occurred_at')->latest('id')->limit(6)->get(),
        ]);
    }
}
