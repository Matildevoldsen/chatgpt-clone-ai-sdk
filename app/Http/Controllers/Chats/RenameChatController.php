<?php

namespace App\Http\Controllers\Chats;

use App\Actions\Chats\RenameChat;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateChatRequest;
use App\Models\Chat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class RenameChatController extends Controller
{
    public function __invoke(
        UpdateChatRequest $request,
        Chat $chat,
        RenameChat $renameChat
    ): RedirectResponse {
        Gate::authorize('update', $chat);

        $renameChat->handle($chat, $request->string('title')->toString());

        return back();
    }
}
