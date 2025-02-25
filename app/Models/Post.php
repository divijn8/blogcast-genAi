<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable=['title','excerpt','body','thumbnail','category_id'];

     // Accessors:for consistent and customized data presentation.
     public function getThumbnailPathAttribute() {
        return 'storage/'.$this->thumbnail;
    }

    public function tags() {
        return $this-> belongsToMany(Tag::class);
    }

    public function category() {
        return $this-> belongsTo(Category::class);
    }
}
