<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Phase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Batch>
 */
class BatchFactory extends Factory
{
    protected $model = Batch::class;

    public function definition(): array
    {
        $batchNum = fake()->numberBetween(1, 5);

        return [
            'phase_id' => Phase::factory(),
            'name' => "Batch {$batchNum}",
            'start_date' => now()->startOfMonth()->format('Y-m-d'),
            'end_date' => now()->addMonths(1)->endOfMonth()->format('Y-m-d'),
        ];
    }
}
