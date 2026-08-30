<?php

namespace Database\Factories;

use App\Models\ClubSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;
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
        $code = (string) fake()->numberBetween(100000, 999999);

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'scheduled_at' => fake()->dateTime(),
            'attendance_code' => Crypt::encryptString($code),
            'attendance_code_hash' => Hash::make($code),
            'attendance_code_expires_at' => now()->addMinutes(ClubSession::OTP_LIFETIME_MINUTES),
            'opened_at' => null,
            'closed_at' => null,
        ];
    }
}
