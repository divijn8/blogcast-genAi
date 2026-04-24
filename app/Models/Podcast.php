<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Podcast extends Model
{
    use HasFactory;

    // ✅ Status constants (IMPORTANT)
    public const STATUS_ACTIVE = 'active';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_DISABLED = 'disabled';

    protected $fillable = [
        'title', 'slug', 'description', 'script_json',
        'audio_path', 'thumbnail', 'duration',
        'category_id', 'author_id', 'published_at',
        'status', 'is_disabled', 'report_count' // ✅ added
    ];

    protected $casts = [
        'script_json' => 'array',
        'published_at' => 'datetime',
    ];

    // =========================
    // Mutators
    // =========================
    public function setTitleAttribute(string $title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = Str::slug($title);
    }

    // =========================
    // Accessors
    // =========================
    public function getThumbnailPathAttribute()
    {
        return 'storage/' . $this->thumbnail;
    }

    public function getUrlAttribute()
    {
        return asset("podcasts/{$this->slug}");
    }

    // =========================
    // Relationships
    // =========================
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->morphMany(Comments::class, 'commentable')
            ->whereNotNull('approved_by')
            ->latest();
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    // =========================
    // Scopes
    // =========================
    public function scopePublished($query)
    {
        return $query
            ->where('published_at', '<=', now('Asia/Kolkata'))
            ->where('status', self::STATUS_ACTIVE)
            ->where('is_disabled', false);
    }

    public function scopeReported($query)
    {
        return $query->where('report_count', '>', 0);
    }

    // =========================
    // Helpers
    // =========================
    public function isActive()
    {
        return ($this->status ?? self::STATUS_ACTIVE) === self::STATUS_ACTIVE;
    }

    public function isUnderReview()
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    public function isDisabled()
    {
        return $this->status === self::STATUS_DISABLED || $this->is_disabled;
    }

    // =========================
    // Reporting Logic (CORE)
    // =========================
    public function handleNewReport()
    {
        // increment reports
        $this->increment('report_count');

        // refresh to get updated value
        $this->refresh();

        // move to under review
        if ($this->report_count >= 3 && $this->status === self::STATUS_ACTIVE) {
            $this->update([
                'status' => self::STATUS_UNDER_REVIEW
            ]);
        }

        // disable content
        if ($this->report_count >= 7 && $this->status !== self::STATUS_DISABLED) {
            $this->update([
                'status' => self::STATUS_DISABLED,
                'is_disabled' => true
            ]);
        }
    }
}
