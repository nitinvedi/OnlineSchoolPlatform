<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'overview',
        'category_id',
        'instructor_id',
        'status',
        'thumbnail_url',
        'published_at',
        'student_count',
        'rating',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'rating'       => 'float',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function liveSessions(): HasMany
    {
        return $this->hasMany(LiveSession::class)->orderBy('scheduled_at');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CourseReview::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Return the thumbnail src: prefer uploaded file, fall back to URL, then null.
     */
    public function getThumbnailSrcAttribute(): ?string
    {
        if ($this->thumbnail_url && str_starts_with($this->thumbnail_url, 'thumbnails/')) {
            return asset('storage/' . $this->thumbnail_url);
        }
        return $this->thumbnail_url ?: null;
    }
}
