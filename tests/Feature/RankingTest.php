<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Evaluation;
use App\Models\SelectionProcess;
use App\Services\RankingCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RankingTest extends TestCase
{
    use RefreshDatabase;

    private function application(SelectionProcess $process, string $date, ?int $d, ?int $i, ?int $t): Application
    {
        $application = Application::factory()->create([
            'selection_process_id' => $process->id,
            'applied_at' => $date,
        ]);
        Evaluation::factory()->create([
            'application_id' => $application->id,
            'documents_score' => $d,
            'interview_score' => $i,
            'technical_score' => $t,
        ]);

        return $application->load('evaluation');
    }

    public function test_ranking_orders_by_final_score_then_date_and_puts_unscored_last(): void
    {
        $process = SelectionProcess::factory()->create();

        $low = $this->application($process, '2025-03-01', 60, 60, 60);
        $unscored = $this->application($process, '2025-03-02', null, null, null);
        $tieLate = $this->application($process, '2025-03-10', 80, 80, 80);
        $tieEarly = $this->application($process, '2025-03-05', 80, 80, 80);
        $top = $this->application($process, '2025-03-03', 95, 90, 92);

        $ranking = (new RankingCalculator)->rank(
            Application::with('evaluation')->where('selection_process_id', $process->id)->get()
        );

        $this->assertSame(
            [$top->id, $tieEarly->id, $tieLate->id, $low->id, $unscored->id],
            $ranking->pluck('application.id')->all()
        );
        $this->assertSame([1, 2, 3, 4, 5], $ranking->pluck('position')->all());
    }

    public function test_ranking_page_renders_for_seeded_data(): void
    {
        $this->seed();

        $this->get(route('ranking.index'))
            ->assertOk()
            ->assertSee('Asistente de Operaciones')
            ->assertSee('DEMO-001');
    }
}
