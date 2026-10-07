<?php

namespace Tests\Feature;

use App\Enums\Stage;
use App\Livewire\DemoResetButton;
use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\SelectionProcess;
use App\Services\DemoResetter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DemoResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_restores_the_initial_dataset(): void
    {
        $this->seed();
        $baseline = Application::orderBy('id')->pluck('stage', 'id')->map->value->all();

        // Simulate a recruiter exploring the demo.
        Application::first()->update(['stage' => Stage::Rejected]);
        SelectionProcess::first()->delete();
        Candidate::factory()->count(3)->create();
        ActivityLog::factory()->create();

        app(DemoResetter::class)->reset();

        $this->assertSame(3, SelectionProcess::count());
        $this->assertSame(16, Candidate::count());
        $this->assertSame(16, Application::count());
        $this->assertSame($baseline, Application::orderBy('id')->pluck('stage', 'id')->map->value->all());
    }

    public function test_reset_is_idempotent(): void
    {
        app(DemoResetter::class)->reset();
        app(DemoResetter::class)->reset();

        $this->assertSame(16, Application::count());
        $this->assertSame('DEMO-001', Candidate::orderBy('id')->first()->code);
    }

    public function test_reset_button_restores_data_and_redirects_to_dashboard(): void
    {
        $this->seed();
        Application::query()->update(['stage' => Stage::Received]);

        Livewire::test(DemoResetButton::class)
            ->call('resetDemo')
            ->assertRedirect(route('dashboard'));

        $this->assertSame(1, Application::where('stage', Stage::Selected->value)->where('id', 1)->count());
    }
}
