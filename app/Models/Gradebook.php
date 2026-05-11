<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gradebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'final_grade',
        'assignment_average',
        'quiz_average',
        'participation_score',
        'assignments_submitted',
        'assignments_graded',
    ];

    protected $casts = [
        'final_grade' => 'decimal:2',
        'assignment_average' => 'decimal:2',
        'quiz_average' => 'decimal:2',
        'participation_score' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function calculateFinalGrade(): void
    {
        $weights = [
            'assignment' => 0.4,
            'quiz' => 0.4,
            'participation' => 0.2,
        ];

        $final = 0;
        if ($this->assignment_average !== null) {
            $final += $this->assignment_average * $weights['assignment'];
        }
        if ($this->quiz_average !== null) {
            $final += $this->quiz_average * $weights['quiz'];
        }
        if ($this->participation_score !== null) {
            $final += $this->participation_score * $weights['participation'];
        }

        $this->update(['final_grade' => $final]);
    }
}
