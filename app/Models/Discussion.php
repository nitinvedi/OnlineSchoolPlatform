<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discussion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'user_id',
        'title',
        'content',
        'status',
        'is_pinned',
        'reply_count',
        'view_count',
        'last_reply_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'last_reply_at' => 'datetime',
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

    public function replies(): HasMany
    {
        return $this->hasMany(DiscussionReply::class)->orderBy('created_at', 'asc');
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function addReply(User $user, string $content, ?int $parentReplyId = null): DiscussionReply
    {
        $reply = $this->replies()->create([
            'user_id' => $user->id,
            'content' => $content,
            'parent_reply_id' => $parentReplyId,
        ]);

        $this->increment('reply_count');
        $this->update(['last_reply_at' => now()]);

        return $reply;
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function close(): void
    {
        $this->update(['status' => 'closed']);
    }
}
