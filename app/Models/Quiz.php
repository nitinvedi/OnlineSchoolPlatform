<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'pass_percentage',
        'max_attempts',
    ];

    protected $casts = [
        'pass_percentage' => 'integer',
        'max_attempts'    => 'integer',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /** Attempts by a specific user */
    public function userAttempts(int $userId): HasMany
    {
        return $this->hasMany(QuizAttempt::class)->where('user_id', $userId);
    }

    /** Total possible points */
    public function maxScore(): int
    {
        return $this->questions->sum('points');
    }
}
