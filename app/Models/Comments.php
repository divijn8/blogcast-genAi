<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $table = 'comments';
    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Polymorphic parent (Post / Podcast / future models)
    public function commentable()
    {
        return $this->morphTo();
    }

    // Registered user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Parent comment (for replies)
    public function parent()
    {
        return $this->belongsTo(Comments::class, 'parent_id');
    }

    // Replies
    public function replies()
    {
        return $this->hasMany(Comments::class, 'parent_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getGuestProfileAttribute()
    {
        if ($this->profile_pic) {
            return "storage/{$this->profile_pic}";
        }

        return "https://ui-avatars.com/api/?name={$this->guest_name}&background=random&rounded=true&bold=true&format=svg";
    }
}
