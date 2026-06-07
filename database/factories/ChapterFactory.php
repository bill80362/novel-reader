<?php

namespace Database\Factories;

use App\Models\Chapter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chapter>
 */
class ChapterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chapter_number' => fake()->numberBetween(1, 100),
            'title' => fake()->sentence(),
            'slug' => fake()->slug(),
            'content' => fake()->paragraph(50),
            'word_count' => 5000,
            'view_count' => 0,
            'published_at' => now(),
        ];
    }
}
