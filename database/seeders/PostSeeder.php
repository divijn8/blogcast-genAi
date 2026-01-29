<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $tags = Tag::all();

        Post::factory()->count(10)->create()->each(function ($post) use ($tags) {
            $post->tags()->sync(
                $tags->random(rand(2, 4))->pluck('id')->toArray()
            );
        });
    }
}
