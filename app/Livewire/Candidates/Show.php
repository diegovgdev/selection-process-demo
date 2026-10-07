<?php

namespace App\Livewire\Candidates;

use App\Enums\Stage;
use App\Models\ActivityLog;
use App\Models\Application;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Ficha de postulante')]
class Show extends Component
{
    public Application $application;

    public string $newStage = '';

    public function mount(Application $application): void
    {
        $this->application = $application->load(['candidate', 'process', 'evaluation']);
        $this->newStage = $application->stage->value;
    }

    public function changeStage(): void
    {
        $this->validate(
            ['newStage' => ['required', Rule::enum(Stage::class)]],
            [
                'newStage.required' => 'Selecciona una etapa.',
                'newStage.enum' => 'La etapa seleccionada no es válida.',
            ],
        );

        $stage = Stage::from($this->newStage);

        if ($stage === $this->application->stage) {
            $this->addError('newStage', 'La postulación ya se encuentra en esa etapa.');

            return;
        }

        if ($stage === Stage::Selected && ! $this->application->process->hasFreeSlots()) {
            $this->addError('newStage', 'No quedan cupos disponibles en este proceso.');

            return;
        }

        $this->application->update(['stage' => $stage]);

        ActivityLog::create([
            'application_id' => $this->application->id,
            'message' => "{$this->application->candidate->name} ({$this->application->candidate->code}) pasó a «{$stage->label()}».",
            'occurred_at' => now(),
        ]);

        session()->flash('status', "Estado actualizado a «{$stage->label()}».");
    }

    public function render()
    {
        $evaluation = $this->application->evaluation;

        return view('livewire.candidates.show', [
            'stages' => Stage::cases(),
            'pipeline' => Stage::pipeline(),
            'scores' => [
                'Evaluación documental' => $evaluation?->documents_score,
                'Entrevista' => $evaluation?->interview_score,
                'Prueba técnica' => $evaluation?->technical_score,
            ],
            'final' => $this->application->finalScore(),
        ]);
    }
}
