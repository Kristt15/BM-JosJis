<?php

namespace Database\Factories;

use App\Enums\VolunteerStatus;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Volunteer>
 */
class VolunteerFactory extends Factory
{
    protected $model = Volunteer::class;

    public function definition(): array
    {
        $majors = [
            'D4 Teknik Informatika',
            'D3 Teknik Informatika',
            'D4 Sains Data Terapan',
            'D3 Teknik Telekomunikasi',
            'D4 Teknik Telekomunikasi',
            'D3 Teknik Elektro Industri',
            'D4 Teknik Elektro Industri',
            'D4 Teknik Mekatronika',
            'D3 Teknik Komputer',
            'D4 Teknologi Rekayasa Multimedia',
        ];

        return [
            'user_id' => User::factory()->volunteer(),
            'nim' => fake()->unique()->numerify('31########'),
            'name' => fake()->name(),
            'major' => fake()->randomElement($majors),
            'generation' => (string) fake()->numberBetween(2022, 2026),
            'phone' => fake()->numerify('08##########'),
            'status' => VolunteerStatus::ACTIVE,
        ];
    }

    public function alumni(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => VolunteerStatus::ALUMNI,
        ]);
    }
}
