<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Technology', 'Lifestyle', 'Business', 'Health'];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat)],
                [
                    'name' => $cat,
                    'type' => 'blog' // <--- Yeh add karna zaroori hai
                ]
            );
        }

        // Podcast ki categories bhi yahi add kar sakte ho testing ke liye
        Category::updateOrCreate(
            ['slug' => 'ai-podcasts'],
            ['name' => 'AI Podcasts', 'type' => 'podcast']
        );
    }
}
