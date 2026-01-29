<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable=['title','slug','excerpt','author_id','body','thumbnail','category_id', 'published_at'];

     // Accessors:for consistent and customized data presentation.
     public function getThumbnailPathAttribute() {
        return 'storage/'.$this->thumbnail;
    }

    // Mutators: to modify or set an attribute's value before it is saved to the database.
    public function setTitleAttribute(string $title) {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = Str::slug($title);
    }


    public function tags() {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function category() {
        return $this-> belongsTo(Category::class);
    }

    public function author() {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute() {
        return asset("blogs/{$this->slug}");
    }

    public function comments()
    {
        return $this->morphMany(Comments::class, 'commentable');
    }

    public function scopePublished($query) {
        return $query->where('published_at', '<=', now('Asia/Kolkata'));
    }

    public function scopeSearch($query) {
        $searchParam = request()->search;
        if($searchParam) {
            $query = $query->where('title', 'like', "%$searchParam%");
        }
        return $query;
    }
}
