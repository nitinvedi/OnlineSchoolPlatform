<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'live_session_id',
        'user_id',
        'joined_at',
        'left_at',
        'duration_minutes',
        'status',
        'notes',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function liveSession(): BelongsTo
    {
        return $this->belongsTo(LiveSession::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function calculateDuration(): void
    {
        if ($this->left_at && $this->joined_at) {
            $minutes = $this->joined_at->diffInMinutes($this->left_at);
            $this->update(['duration_minutes' => $minutes]);
        }
    }

    public function markLeft(): void
    {
        if (!$this->left_at) {
            $this->update(['left_at' => now()]);
            $this->calculateDuration();
        }
    }

    public function isAttended(): bool
    {
        return $this->status === 'attended';
    }

    public function isLate(): bool
    {
        return $this->status === 'late';
    }

    public function isAbsent(): bool
    {
        return $this->status === 'absent';
    }
}
