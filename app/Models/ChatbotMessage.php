<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotMessage extends Model
{
    protected $table = 'chatbot_messages';

    protected $fillable = [
        'user_id',
        'session_id',
        'message',
        'response',
        'message_type',
        'metadata',
        'response_time',
        'is_helpful',
        'feedback_note',
        'status',
    ];

    protected $casts = [
        'metadata' => 'array',
        'response_time' => 'float',
        'is_helpful' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: A message belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Get messages for a specific session
     */
    public function scopeSession($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope: Get completed messages only
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Get messages that were helpful
     */
    public function scopeHelpful($query)
    {
        return $query->where('is_helpful', true);
    }

    /**
     * Scope: Get recent messages
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get conversation context for AI (last N messages)
     */
    public static function getSessionContext(string $sessionId, int $limit = 10)
    {
        return self::session($sessionId)
            ->completed()
            ->latest()
            ->take($limit)
            ->get()
            ->reverse()
            ->map(fn($msg) => [
                'role' => 'user',
                'content' => $msg->message,
            ])
            ->values()
            ->all();
    }
}
