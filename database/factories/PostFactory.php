<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'title' => fake()->sentence,
            'excerpt' => fake()->paragraph,
            'body' => fake()->paragraphs(random_int(5, 8), true),
            'category_id' => Category::all('id')->random()->id,
            'thumbnail' => 'thumbnails/' . random_int(1, 20) . ".jpg"
        ];
    }
}
