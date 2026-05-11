<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participation extends Model
{
    use HasFactory;

    protected $fillable = [
        'live_session_id',
        'user_id',
        'type',
        'content',
        'score',
    ];

    protected $casts = [
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

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'question_asked' => 'Question Asked',
            'comment' => 'Comment',
            'poll_response' => 'Poll Response',
            'raised_hand' => 'Raised Hand',
            default => ucfirst($this->type),
        };
    }
}
