<?php

namespace App\Jobs;

use App\Mail\NewBlogNotification;
use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewPostNotificationJob implements ShouldQueue
{
    use Queueable,InteractsWithQueue,SerializesModels;

    protected $email;
    protected $post;

    /**
     * Create a new job instance.
     */
    public function __construct(Post $post,string $email)
    {
        $this->post = $post;
        $this->email=$email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new NewBlogNotification($this->post));
    }
}
