<?php

namespace App\Http\Controllers\Chats;

use App\Actions\Chats\ListChats;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ChatPageController extends Controller
{
    public function index(Request $request, ListChats $listChats): Response
    {
        return Inertia::render('Chat/Index', [
            'chats' => ChatResource::collection($listChats->handle($request->user())),
            'models' => ['gpt-5.4-mini', 'gpt-5.4'],
        ]);
    }

    public function show(Request $request, Chat $chat, ListChats $listChats): Response
    {
        Gate::authorize('view', $chat);

        $chat->load(['messages' => fn ($query) => $query->oldest()]);

        return Inertia::render('Chat/Show', [
            'chats' => ChatResource::collection($listChats->handle($request->user())),
            'chat' => ChatResource::make($chat),
            'models' => ['gpt-5.4-mini', 'gpt-5.4'],
        ]);
    }
}
