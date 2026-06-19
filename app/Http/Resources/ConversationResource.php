<?php

namespace App\Http\Resources;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Conversation
 */
class ConversationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (! $this->resource instanceof Conversation) {
            return [];
        }

        return [
            'id' => $this->resource->id,
            'role' => $this->resource->role,
            'content' => $this->resource->content,
            'created_at' => $this->resource->getAttribute('created_at')?->toIso8601String(),
        ];
    }
}
