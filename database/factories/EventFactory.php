<?php

namespace Database\Factories;

use App\Enums\EventType;
use App\Models\Batch;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $num = fake()->numberBetween(1, 8);

        return [
            'batch_id' => Batch::factory(),
            'name' => "Pertemuan {$num}",
            'type' => EventType::REGULAR,
            'event_date' => fake()->dateTimeBetween('now', '+2 months'),
            'location' => 'Sekolah Rakyat Kejawan',
            'description' => 'Kegiatan pembelajaran interaktif dan pendampingan belajar bersama adik-adik SRK.',
        ];
    }

    public function regular(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => EventType::REGULAR,
        ]);
    }

    public function special(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Kegiatan Spesial ' . fake()->word(),
            'type' => EventType::SPECIAL,
        ]);
    }

    public function aksi(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'BM AKSI ' . fake()->year(),
            'type' => EventType::AKSI,
        ]);
    }
}
