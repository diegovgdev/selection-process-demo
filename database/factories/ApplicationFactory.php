<?php

namespace Database\Factories;

use App\Enums\Stage;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\SelectionProcess;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Application> */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        return [
            'selection_process_id' => SelectionProcess::factory(),
            'candidate_id' => Candidate::factory(),
            'stage' => Stage::Received,
            'applied_at' => fake()->dateTimeBetween('2025-03-01', '2025-03-31')->format('Y-m-d'),
            'comment' => 'Comentario ficticio de evaluación.',
        ];
    }
}
