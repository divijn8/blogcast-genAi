<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = collect(['Java', 'PHP', 'Laravel', 'Framework', 'Programming', 'Cricket', 'F1', 'Football', 'Coding','Hacking','Rose']);
        $tags->each(function($tagName) {
            Tag::create(['name' => $tagName]);
        });

    }
}
