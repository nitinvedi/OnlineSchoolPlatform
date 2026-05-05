<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LiveSession extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'course_id',
        'instructor_id',
        'title',
        'description',
        'scheduled_at',
        'started_at',
        'ended_at',
        'status',
        'jitsi_room',
        'max_duration_minutes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($session) {
            if (empty($session->jitsi_room)) {
                $session->jitsi_room = 'ls-' . Str::random(10) . '-' . time();
            }
        });
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')->orderBy('scheduled_at', 'asc');
    }

    public function scopeLive($query)
    {
        return $query->where('status', 'live');
    }

    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    // Helpers
    public function isLive(): bool
    {
        return $this->status === 'live';
    }

    public function isUpcoming(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isEnded(): bool
    {
        return $this->status === 'ended';
    }
}
