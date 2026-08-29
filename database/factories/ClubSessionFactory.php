<?php

namespace Database\Factories;

use App\Models\ClubSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<ClubSession>
 */
class ClubSessionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'scheduled_at' => fake()->dateTime(),
            'attendance_code_hash' => Hash::make((string) fake()->numberBetween(100000, 999999)),
            'opened_at' => null,
            'closed_at' => null,
        ];
    }
}
