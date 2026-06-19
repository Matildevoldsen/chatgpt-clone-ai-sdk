<?php

namespace App\Http\Controllers\Chats;

use App\Actions\Chats\DeleteChat;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class DeleteChatController extends Controller
{
    public function __invoke(Chat $chat, DeleteChat $deleteChat): RedirectResponse
    {
        Gate::authorize('delete', $chat);

        $deleteChat->handle($chat);

        return to_route('chat');
    }
}
