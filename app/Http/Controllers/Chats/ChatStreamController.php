<?php

namespace App\Http\Controllers\Chats;

use App\Ai\Agents\ChatAgent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChatMessageRequest;
use Illuminate\Support\Str;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Streaming\Events\TextDelta;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatStreamController extends Controller
{
    public function __invoke(StoreChatMessageRequest $request): StreamedResponse
    {
        $message = $request->string('message')->toString();
        $chatId = $request->string('chat')->toString();

        $chat = $chatId !== ''
            ? $request->user()->chats()->findOrFail($chatId)
            : $request->user()->chats()->create([
                'title' => Str::limit($message, 50, preserveWords: true),
            ]);

        $stream = ChatAgent::make()
            ->continue($chat->id, $request->user())
            ->stream(
                $message,
                provider: Lab::Azure,
                model: $request->string('model', 'gpt-5.4-mini')->toString(),
            );

        return response()->stream(function () use ($stream): void {
            foreach ($stream as $event) {
                if ($event instanceof TextDelta) {
                    echo $event->delta;

                    if (ob_get_level() > 0) {
                        ob_flush();
                    }

                    flush();
                }
            }
        }, 200, [
            'Cache-Control' => 'no-cache, no-transform',
            'Content-Type' => 'text/plain; charset=UTF-8',
            'X-Accel-Buffering' => 'no',
            'X-Chat-Id' => $chat->id,
        ]);
    }
}
