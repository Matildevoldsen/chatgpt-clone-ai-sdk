<?php

namespace App\Actions\Chats;

use App\Models\Chat;

class RenameChat
{
    public function handle(Chat $chat, string $title): Chat
    {
        $chat->forceFill(['title' => $title])->save();

        return $chat->refresh();
    }
}
