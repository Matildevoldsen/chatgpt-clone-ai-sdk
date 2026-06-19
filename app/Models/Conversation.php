<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $conversation_id
 * @property int|null $user_id
 * @property string $role
 * @property string $content
 */
class Conversation extends Model
{
    use HasUuids;

    protected $guarded = [];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'attachments' => 'array',
        'tool_calls' => 'array',
        'tool_results' => 'array',
        'usage' => 'array',
        'meta' => 'array',
    ];

    /**
     * @return BelongsTo<Chat, $this>
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'conversation_id');
    }

    public function getTable(): string
    {
        return config('ai.conversations.tables.messages', 'agent_conversation_messages');
    }

    public function getConnectionName(): ?string
    {
        return config('ai.conversations.connection');
    }
}
