<?php

namespace Database\Factories;

use App\Models\Child;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Child>
 */
class ChildFactory extends Factory
{
    protected $model = Child::class;

    public function definition(): array
    {
        $firstNames = [
            'Muhammad', 'Ahmad', 'Dimas', 'Budi', 'Rizky', 'Fajar', 'Bayu', 'Aditya',
            'Siti', 'Nur', 'Aisyah', 'Putri', 'Dewi', 'Indah', 'Lestari', 'Anisa',
        ];
        $lastNames = [
            'Saputra', 'Pratama', 'Hidayat', 'Ramadhan', 'Kurniawan', 'Santoso',
            'Wulandari', 'Anggraini', 'Rahmawati', 'Oktaviani', 'Kusuma', 'Permata',
        ];

        $gender = fake()->randomElement(['L', 'P']);
        $firstName = fake()->randomElement($firstNames);
        $lastName = fake()->randomElement($lastNames);
        $fullName = "{$firstName} {$lastName}";
        $gradeNumber = fake()->numberBetween(1, 6);

        $schools = [
            'SDN Kejawan Putih I Surabaya',
            'SDN Kejawan Putih II Surabaya',
            'MI Al-Hidayah Kejawan Putih',
            'SDN Mulyorejo I Surabaya',
        ];

        $addresses = [
            'Jl. Kejawan Putih Tambak No. ' . fake()->numberBetween(1, 120) . ', Mulyorejo, Surabaya',
            'Jl. Kejawan Putih Gang ' . fake()->numberBetween(1, 10) . ', Surabaya',
            'Jl. Tambak Wedi Baru No. ' . fake()->numberBetween(1, 80) . ', Kenjeran, Surabaya',
            'Jl. Mulyorejo Utara No. ' . fake()->numberBetween(1, 50) . ', Surabaya',
        ];

        $parentTitle = fake()->randomElement(['Bpk. ', 'Ibu ']);
        $parentName = $parentTitle . fake()->randomElement($lastNames);
        $parentPhone = fake()->numerify('08##########');

        return [
            'name' => $fullName,
            'nickname' => $firstName,
            'birth_date' => fake()->dateTimeBetween('-12 years', '-6 years')->format('Y-m-d'),
            'gender' => $gender,
            'grade' => "Kelas {$gradeNumber} SD",
            'school' => fake()->randomElement($schools),
            'address' => fake()->randomElement($addresses),
            'notes' => "Wali: {$parentName} ({$parentPhone})",
        ];
    }
}
