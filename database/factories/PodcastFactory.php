<?php

namespace Database\Factories;

use App\Models\Podcast;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PodcastFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'slug' => \Illuminate\Support\Str::slug($this->faker->sentence()),
            'description' => $this->faker->paragraph(),
            'script_json' => [
                ['speaker' => 'Aryan', 'text' => 'Hi everyone!'],
                ['speaker' => 'Sara', 'text' => 'Welcome to the show.']
            ],
            'audio_path' => 'podcasts/sample.mp3',
            'thumbnail' => 'podcasts/default.jpg',
            'duration' => rand(300, 1200),
            'author_id' => User::first()->id ?? User::factory(),
            'category_id' => Category::where('type', 'podcast')->inRandomOrder()->first()?->id
                             ?? Category::factory()->create(['type' => 'podcast'])->id,
        ];
    }
}
