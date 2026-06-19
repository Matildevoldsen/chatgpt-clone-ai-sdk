<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { useStream } from '@laravel/stream-vue';
import { computed, ref } from 'vue';
import ChatComposer from '@/components/chat/ChatComposer.vue';
import ChatMessages from '@/components/chat/ChatMessages.vue';
import ChatSidebar from '@/components/chat/ChatSidebar.vue';
import RenameChatDialog from '@/components/chat/RenameChatDialog.vue';
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from '@/components/ui/sidebar';
import type { Chat, Conversation } from '@/types';

const props = defineProps<{
    chats: Chat[];
    activeChat: Chat | null;
    models: string[];
}>();

const selectedModel = ref(props.models[0] ?? 'gpt-5.4-mini');
const prompt = ref('');
const renameOpen = ref(false);
const chatToRename = ref<Chat | null>(null);
const activeChatId = ref<string | null>(props.activeChat?.id ?? null);
const messages = ref<Conversation[]>(props.activeChat?.messages ?? []);

const temporaryId = () =>
    `local-${Date.now().toString(36)}-${Math.random().toString(36).slice(2)}`;

const openRename = (chat: Chat) => {
    chatToRename.value = chat;
    renameOpen.value = true;
};

const refreshChat = () => {
    router.reload({
        only: ['chats', 'chat'],
        onSuccess: () => {
            messages.value = props.activeChat?.messages ?? messages.value;
        },
    });
};

const appendAssistantText = (text: string) => {
    messages.value[messages.value.length - 1].content += text;
};

const { isFetching, isStreaming, send } = useStream<{
    chat: string | null;
    message: string;
    model: string;
}>('/chat/stream', {
    onResponse: (response) => {
        const chatId = response.headers.get('X-Chat-Id');

        if (chatId) {
            activeChatId.value = chatId;
            window.history.replaceState({}, '', `/chat/${chatId}`);
        }
    },
    onData: appendAssistantText,
    onFinish: refreshChat,
    onError: () => {
        messages.value[messages.value.length - 1].content =
            'The request failed. Check Azure credentials and try again.';
    },
});

const isBusy = computed(() => isFetching.value || isStreaming.value);

const submit = () => {
    const content = prompt.value.trim();

    if (content.length === 0 || isBusy.value) {
        return;
    }

    prompt.value = '';
    messages.value.push({
        id: temporaryId(),
        role: 'user',
        content,
        created_at: new Date().toISOString(),
    });
    messages.value.push({
        id: temporaryId(),
        role: 'assistant',
        content: '',
        created_at: new Date().toISOString(),
    });

    send({
        chat: activeChatId.value,
        message: content,
        model: selectedModel.value,
    });
};
</script>

<template>
    <SidebarProvider class="h-dvh min-h-0 overflow-hidden bg-background">
        <ChatSidebar
            :chats="chats"
            :active-chat-id="activeChatId"
            @rename="openRename"
        />

        <SidebarInset class="min-w-0 overflow-hidden">
            <div class="flex h-12 items-center border-b px-4 md:hidden">
                <SidebarTrigger />
            </div>
            <ChatMessages :messages="messages" :is-streaming="isStreaming" />
            <ChatComposer
                v-model:message="prompt"
                v-model:model="selectedModel"
                :models="models"
                :disabled="isBusy"
                @submit="submit"
            />
        </SidebarInset>

        <RenameChatDialog v-model:open="renameOpen" :chat="chatToRename" />
    </SidebarProvider>
</template>
