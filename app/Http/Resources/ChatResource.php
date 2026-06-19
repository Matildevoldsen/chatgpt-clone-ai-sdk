<?php

namespace App\Http\Resources;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Chat
 */
class ChatResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (! $this->resource instanceof Chat) {
            return [];
        }

        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'updated_at' => $this->resource->getAttribute('updated_at')?->toIso8601String(),
            'messages' => ConversationResource::collection(
                $this->whenLoaded('messages')
            ),
        ];
    }
}
