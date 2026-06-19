<?php

namespace App\Ai\Agents;

use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Azure)]
#[Model('gpt-5.4-mini')]
class ChatAgent implements Agent, Conversational
{
    use Promptable;
    use RemembersConversations;

    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
        You are ChatGPT for a Laravel meetup demo in India.
        Keep answers clear, practical, and friendly. Prefer concise examples,
        explain Laravel and Vue decisions simply, and avoid unnecessary jargon.
        PROMPT;
    }
}
