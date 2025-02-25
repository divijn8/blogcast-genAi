<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateSlugsForPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to generate slugs for posts....');

        Post::whereNull('slug')
            ->chunk(100, function($posts) {
                foreach ($posts as $post) {
                    $post->slug = Str::slug($post->title);
                    $post->save();
                    $this->info("Updated Post ID: {$post->id} with its slug...");
                }
            });
        $this->info('Slug generation process completed...');

    }
}
