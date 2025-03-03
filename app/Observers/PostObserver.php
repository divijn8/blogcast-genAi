<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Post;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        Category::where('id', $post->category_id)->increment('posts_count');
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        if($post->isDirty('category_id')) {
            Category::where('id', $post->getOriginal('category_id'))->decrement('posts_count');
            Category::where('id', $post->category_id)->increment('posts_count');
        }
    }


    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        Category::where('id', $post->category_id)->decrement('posts_count');
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}
