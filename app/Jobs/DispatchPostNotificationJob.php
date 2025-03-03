<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Concerns\InteractsWithInput;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DispatchPostNotificationJob implements ShouldQueue
{
    use Queueable,InteractsWithQueue,SerializesModels;

    protected $postId;

    /**
     * Create a new job instance.
     */
    public function __construct($postId)
    {
        $this->postId = $postId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $post = Post::find($this->postId);
            if(!$post){
                Log::error('Post not found not found in dispachpostnotificationjob'.$this->postId);

            return;
            }
            Subscriber::chunk(1000,function($subscribers) use($post){
                foreach($subscribers as $subscriber){
                    SendNewPostNotificationJob::dispatch($post,$subscriber->email);
                }
            });
    }
}
