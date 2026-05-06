<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Notifications\CertificateEarned;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'enrolled_at',
        'completed_at',
        'progress_percent',
        'lessons_completed',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function completedLessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'enrollment_lesson')
            ->withTimestamps()
            ->withPivot('completed_at');
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function isLessonCompleted(Lesson $lesson): bool
    {
        return $this->completedLessons()->where('lesson_id', $lesson->id)->exists();
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    public function checkCompletion(): void
    {
        $totalLessons = $this->course->lessons()->count();
        $completedCount = $this->completedLessons()->count();

        if ($totalLessons > 0 && $completedCount === $totalLessons) {
            if (!$this->isCompleted()) {
                $this->update(['completed_at' => now()]);
            }

            if (!$this->certificate()->exists()) {
                $certificate = $this->certificate()->create([
                    'user_id' => $this->user_id,
                    'course_id' => $this->course_id,
                    'issued_at' => now(),
                ]);

                // Send certificate notification
                $this->user->notify(new CertificateEarned($certificate));
            }
        }
    }
}
