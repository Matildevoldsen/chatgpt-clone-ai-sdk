<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property int|null $user_id
 * @property string $title
 */
class Chat extends Model
{
    use HasUuids;

    protected $guarded = [];

    /**
     * @return HasMany<Conversation, $this>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Conversation::class, 'conversation_id');
    }

    public function getTable(): string
    {
        return config('ai.conversations.tables.conversations', 'agent_conversations');
    }

    public function getConnectionName(): ?string
    {
        return config('ai.conversations.connection');
    }
}
