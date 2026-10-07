<?php

namespace Database\Factories;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Candidate> */
class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    public function definition(): array
    {
        return [
            'code' => 'DEMO-F'.fake()->unique()->numberBetween(1000, 9999),
            'name' => fake()->name(),
        ];
    }
}
