<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Podcast extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'script_json',
        'audio_path', 'thumbnail', 'duration', 'category_id', 'author_id', 'published_at'
    ];

    protected $casts = [
        'script_json' => 'array',
        'published_at' => 'datetime',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Generic Comments (Polymorphic)
    public function comments() {
        return $this->morphMany(Comments::class, 'commentable');
    }

    // Generic Tags (Polymorphic)
    public function tags() {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
