<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'level' => fake()->randomElement(['Level 1 - Beg', 'Level 2 - Basic', 'Level 3 - Int', 'Level 4 - Adv', 'Professional Class', 'Special Prep']),
            'teacher' => fake()->name().', '.fake()->randomElement(['M.A.', 'B.E.d', 'MBA', 'M.Sc.']),
            'description' => fake()->paragraph(),
            'image_path' => null,
            'sort_order' => 0,
            'is_published' => true,
        ];
    }
}
