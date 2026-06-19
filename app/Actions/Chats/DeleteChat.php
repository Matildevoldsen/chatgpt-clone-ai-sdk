<?php

namespace App\Actions\Chats;

use App\Models\Chat;

class DeleteChat
{
    public function handle(Chat $chat): void
    {
        $chat->messages()->delete();
        $chat->delete();
    }
}
