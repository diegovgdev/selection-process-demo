<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Evaluation;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Evaluation> */
class EvaluationFactory extends Factory
{
    protected $model = Evaluation::class;

    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'documents_score' => fake()->numberBetween(40, 100),
            'interview_score' => fake()->numberBetween(40, 100),
            'technical_score' => fake()->numberBetween(40, 100),
        ];
    }
}
