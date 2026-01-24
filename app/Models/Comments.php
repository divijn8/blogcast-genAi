<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $table = 'comments';
    protected $guarded = [];

    public function posts(){
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function parent(){
        return $this->belongsTo(Comments::class,'parent_id');
    }

    public function replies(){
        return $this->hasMany(Comments::class,'parent_id');
    }

    public function getGuestProfileAttribute() {
        $url = "https://ui-avatars.com/api/?name={$this->guest_name}&background=random&rounded=true&bold=true&format=svg";
        return $this->profile_pic ? "storage/{$this->profile_pic}" : $url;
    }
}
