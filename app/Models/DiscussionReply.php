<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscussionReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'discussion_id',
        'user_id',
        'parent_reply_id',
        'content',
        'likes_count',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function discussion(): BelongsTo
    {
        return $this->belongsTo(Discussion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parentReply(): BelongsTo
    {
        return $this->belongsTo(DiscussionReply::class, 'parent_reply_id');
    }

    public function childReplies(): HasMany
    {
        return $this->hasMany(DiscussionReply::class, 'parent_reply_id')->orderBy('created_at', 'asc');
    }

    public function like(): void
    {
        $this->increment('likes_count');
    }

    public function unlike(): void
    {
        if ($this->likes_count > 0) {
            $this->decrement('likes_count');
        }
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function approve(): void
    {
        $this->update(['status' => 'approved']);
    }

    public function hide(): void
    {
        $this->update(['status' => 'hidden']);
    }
}
