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
        'thumbnail_url',
        'published_at',
        'student_count',
        'rating',
        'price',
        'is_free',
        'learning_outcomes',
        'prerequisites',
        'difficulty_level',
        'is_bestseller',
        'sale_ends_at',
        'articles_count',
        'resources_count',
        'status',
        'level',
        'language',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'sale_ends_at' => 'datetime',
        'rating'       => 'float',
        'price'        => 'decimal:2',
        'learning_outcomes' => 'array',
        'prerequisites' => 'array',
        'is_bestseller' => 'boolean',
        'is_free' => 'boolean',
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

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class)->orderBy('order');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class)->latest();
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }

    public function reviewSubmissions(): HasMany
    {
        return $this->hasMany(ReviewSubmission::class);
    }

    public function gradebooks(): HasMany
    {
        return $this->hasMany(Gradebook::class);
    }

    /**
     * Check if course is currently on sale
     */
    public function isOnSale(): bool
    {
        return $this->sale_ends_at && $this->sale_ends_at->isFuture();
    }

    /**
     * Get sale time remaining in human readable format
     */
    public function getSaleTimeRemaining(): ?string
    {
        if (!$this->isOnSale()) {
            return null;
        }
        
        return $this->sale_ends_at->diffForHumans();
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
