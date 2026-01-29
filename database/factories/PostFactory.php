<?php

namespace Database\Factories;

use App\Models\Category;
use Carbon\Carbon;
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
            'category_id' => Category::where('type', 'blog')->inRandomOrder()->first()?->id
                             ?? Category::factory()->create(['type' => 'blog'])->id,
            'thumbnail' => 'thumbnails/' . random_int(1, 35) . ".jpg",
            'published_at' => Carbon::now(),
        ];
    }
}
