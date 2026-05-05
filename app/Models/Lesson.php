<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lesson extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'content',
        'description',
        'course_id',
        'order',
        'video_url',
        'duration_minutes',
        'type',
        'is_free',
    ];

    protected $casts = [
        'is_free' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }
}
