<?php

namespace Database\Factories;

use App\Enums\ProgramStatus;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    protected $model = Program::class;

    public function definition(): array
    {
        $year = fake()->numberBetween(2024, 2028);

        return [
            'name' => "Bidikmisi Mengajar {$year}",
            'year' => $year,
            'status' => ProgramStatus::PLANNING,
            'description' => "Penyelenggaraan Bidikmisi Mengajar PENS tahun {$year} di Sekolah Rakyat Kejawan.",
            'start_date' => "{$year}-01-15",
            'end_date' => "{$year}-12-20",
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProgramStatus::ACTIVE,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProgramStatus::COMPLETED,
        ]);
    }
}
