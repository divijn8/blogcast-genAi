<?php

namespace Database\Seeders;

use App\Models\Podcast;
use App\Models\Category;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PodcastSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::updateOrCreate(
            ['slug' => 'tech-talks'],
            ['name' => 'Tech Talks', 'type' => 'podcast']
        );

        $admin = User::first() ?? User::factory()->create();

        $tags = collect(['AI', 'Automation', 'Future'])->map(function($tagName) {
            return Tag::firstOrCreate(['name' => $tagName], ['slug' => Str::slug($tagName)]);
        });

        $podcast = Podcast::create([
            'title' => 'The Future of AI in Maharashtra',
            'slug' => Str::slug('The Future of AI in Maharashtra'),
            'description' => 'A conversational deep dive into how AI is impacting local tech hubs like Mumbai and Pune.',
            'category_id' => $category->id,
            'author_id' => $admin->id,
            'audio_path' => 'podcasts/sample-audio.mp3', // Make sure this dummy path is there
            'thumbnail' => 'https://via.placeholder.com/640x480.png/004466?text=AI+Podcast',
            'duration' => 450, // 7.5 minutes
            'published_at' => now(),
            'view_count' => mt_rand(100, 5000),
            'script_json' => [
                [
                    'speaker' => 'Aryan (Host)',
                    'text' => 'Namaste doston! Welcome to BlogCast. Aaj humare saath ek special guest hain.'
                ],
                [
                    'speaker' => 'Sara (Expert)',
                    'text' => 'Dhanyawad Aryan! AI India mein, khaas kar Maharashtra ke startups mein bohot tezi se badh raha hai.'
                ],
                [
                    'speaker' => 'Aryan (Host)',
                    'text' => 'Sahi kaha. Kya aapko lagta hai local language support isme bada role play karega?'
                ],
                [
                    'speaker' => 'Sara (Expert)',
                    'text' => 'Bilkul! LLMs ab Marathi aur Hindi context ko behtar samajh rahe hain.'
                ]
            ],
        ]);

        $podcast->tags()->sync($tags->pluck('id'));
    }
}
