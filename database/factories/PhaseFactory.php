<?php

namespace Database\Factories;

use App\Enums\PhaseType;
use App\Models\Phase;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Phase>
 */
class PhaseFactory extends Factory
{
    protected $model = Phase::class;

    public function definition(): array
    {
        return [
            'program_id' => Program::factory(),
            'name' => 'Teaching Phase',
            'type' => PhaseType::TEACHING,
            'sequence' => 1,
            'start_date' => now()->startOfMonth()->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->endOfMonth()->format('Y-m-d'),
        ];
    }

    public function teaching(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Teaching Phase',
            'type' => PhaseType::TEACHING,
        ]);
    }

    public function closing(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Closing Phase',
            'type' => PhaseType::CLOSING,
        ]);
    }

    public function special(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Special Phase',
            'type' => PhaseType::SPECIAL,
        ]);
    }
}
