<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_page_lists_the_users_chats(): void
    {
        $user = User::factory()->create();
        $chat = $user->chats()->create([
            'title' => 'Meetup prep',
        ]);

        $chat->messages()->create([
            'user_id' => $user->id,
            'agent' => 'ChatAgent',
            'role' => 'user',
            'content' => 'Plan the demo.',
            'attachments' => '[]',
            'tool_calls' => '[]',
            'tool_results' => '[]',
            'usage' => '[]',
            'meta' => '[]',
        ]);

        $this->actingAs($user)
            ->get(route('chat.show', $chat))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Chat/Show')
                ->where('chats.data.0.title', 'Meetup prep')
                ->where('chat.data.messages.0.content', 'Plan the demo.')
            );
    }

    public function test_user_can_rename_their_chat(): void
    {
        $user = User::factory()->create();
        $chat = $user->chats()->create([
            'title' => 'Old title',
        ]);

        $this->actingAs($user)
            ->patch(route('chat.update', $chat), ['title' => 'New title'])
            ->assertRedirect();

        $this->assertSame('New title', $chat->refresh()->getAttribute('title'));
    }

    public function test_user_cannot_rename_someone_elses_chat(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $chat = $owner->chats()->create([
            'title' => 'Private chat',
        ]);

        $this->actingAs($user)
            ->patch(route('chat.update', $chat), ['title' => 'Nope'])
            ->assertForbidden();
    }

    public function test_user_can_delete_their_chat_messages_with_the_chat(): void
    {
        $user = User::factory()->create();
        $chat = $user->chats()->create([
            'title' => 'Throwaway',
        ]);
        $message = $chat->messages()->create([
            'user_id' => $user->id,
            'agent' => 'ChatAgent',
            'role' => 'assistant',
            'content' => 'Saved reply.',
            'attachments' => '[]',
            'tool_calls' => '[]',
            'tool_results' => '[]',
            'usage' => '[]',
            'meta' => '[]',
        ]);

        $this->actingAs($user)
            ->delete(route('chat.destroy', $chat))
            ->assertRedirect(route('chat'));

        $this->assertDatabaseMissing('agent_conversations', ['id' => $chat->id]);
        $this->assertDatabaseMissing('agent_conversation_messages', ['id' => $message->id]);
    }
}
