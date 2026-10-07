<?php

namespace Database\Factories;

use App\Models\SelectionProcess;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<SelectionProcess> */
class SelectionProcessFactory extends Factory
{
    protected $model = SelectionProcess::class;

    public function definition(): array
    {
        return [
            'name' => fake()->jobTitle().' (DEMO)',
            'description' => 'Proceso ficticio generado para pruebas.',
            'slots' => fake()->numberBetween(1, 4),
            'status' => 'open',
        ];
    }
}
