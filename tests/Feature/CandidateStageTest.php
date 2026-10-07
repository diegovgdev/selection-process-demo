<?php

namespace Tests\Feature;

use App\Enums\Stage;
use App\Livewire\Candidates\Index;
use App\Livewire\Candidates\Show;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CandidateStageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_main_pages_load_without_authentication(): void
    {
        foreach (['dashboard', 'candidates.index', 'processes.index', 'ranking.index'] as $route) {
            $this->get(route($route))->assertOk()->assertSee('DEMO · Datos ficticios');
        }
    }

    public function test_search_and_status_filter(): void
    {
        Livewire::test(Index::class)
            ->set('search', 'Valentina')
            ->assertSee('Valentina Arce Montt')
            ->assertDontSee('Matías Corvalán Ibarra')
            ->set('search', '')
            ->set('status', Stage::Rejected->value)
            ->assertSee('Antonella Fuentes Rey')
            ->assertDontSee('Valentina Arce Montt');
    }

    public function test_stage_can_be_changed_and_is_logged(): void
    {
        $application = Application::where('stage', Stage::Received->value)->firstOrFail();

        Livewire::test(Show::class, ['application' => $application])
            ->set('newStage', Stage::DocumentReview->value)
            ->call('changeStage')
            ->assertHasNoErrors();

        $this->assertSame(Stage::DocumentReview, $application->fresh()->stage);
        $this->assertDatabaseHas('activity_logs', ['application_id' => $application->id]);
    }

    public function test_invalid_stage_is_rejected(): void
    {
        $application = Application::firstOrFail();

        Livewire::test(Show::class, ['application' => $application])
            ->set('newStage', 'hacked')
            ->call('changeStage')
            ->assertHasErrors('newStage');
    }

    public function test_cannot_select_when_no_slots_are_left(): void
    {
        // Process 1 has 2 slots, both already covered in the seed data.
        $application = Application::where('selection_process_id', 1)
            ->where('stage', Stage::Technical->value)
            ->firstOrFail();

        Livewire::test(Show::class, ['application' => $application])
            ->set('newStage', Stage::Selected->value)
            ->call('changeStage')
            ->assertHasErrors('newStage');

        $this->assertSame(Stage::Technical, $application->fresh()->stage);
    }
}
