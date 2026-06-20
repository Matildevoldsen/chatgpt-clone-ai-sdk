<script setup lang="ts">
import { Bot, User } from '@lucide/vue';
import { StreamMarkdown } from 'streamdown-vue';
import type { Conversation } from '@/types';

defineProps<{
    messages: Conversation[];
    isStreaming: boolean;
}>();

const shikiTheme = {
    light: 'github-light',
    dark: 'github-dark',
};
</script>

<template>
    <div class="flex-1 overflow-y-auto">
        <div
            class="flex min-h-full w-full flex-col gap-6 px-4 py-8 sm:px-6 lg:px-10"
        >
            <div
                v-if="messages.length === 0"
                class="flex flex-1 items-center justify-center"
            >
                <div class="max-w-xl text-center">
                    <h1
                        class="text-3xl font-semibold tracking-normal text-zinc-950 dark:text-zinc-50"
                    >
                        What should we build with Laravel AI?
                    </h1>
                    <p class="mt-3 text-base text-zinc-500">
                        Ask a focused question and watch the Azure GPT response
                        stream in.
                    </p>
                </div>
            </div>

            <article
                v-for="message in messages"
                :key="message.id"
                class="grid w-full max-w-none grid-cols-[2rem_minmax(0,1fr)] gap-4"
            >
                <div
                    class="mt-1 flex size-8 shrink-0 items-center justify-center rounded-md"
                    :class="
                        message.role === 'user'
                            ? 'bg-zinc-900 text-white'
                            : 'bg-emerald-600 text-white'
                    "
                >
                    <User v-if="message.role === 'user'" class="size-4" />
                    <Bot v-else class="size-4" />
                </div>

                <div class="min-w-0 overflow-hidden">
                    <div
                        class="mb-1 text-xs font-medium text-zinc-500 uppercase"
                    >
                        {{ message.role === 'user' ? 'You' : 'ChatGPT' }}
                    </div>
                    <div
                        v-if="message.role === 'user'"
                        class="w-full max-w-none text-[15px] leading-7 break-words whitespace-pre-wrap text-zinc-800 dark:text-zinc-100"
                    >
                        {{ message.content }}
                    </div>
                    <StreamMarkdown
                        v-else
                        :content="message.content"
                        class="streamdown-chat w-full max-w-none text-[15px] leading-7 text-zinc-800 dark:text-zinc-100"
                        :code-block-hide-download="true"
                        :shiki-theme="shikiTheme"
                    />
                    <!-- Plain assistant renderer, no Streamdown:
                    <div
                        v-else
                        class="whitespace-pre-wrap break-words text-[15px] leading-7 text-zinc-800 dark:text-zinc-100"
                    >
                        {{ message.content }}
                    </div>
                    -->
                </div>
            </article>

            <div v-if="isStreaming" class="pl-11 text-sm text-zinc-500">
                Streaming...
            </div>
        </div>
    </div>
</template>
