<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ChatWorkspace from '@/components/chat/ChatWorkspace.vue';
import type { Chat } from '@/types';

defineProps<{
    chats: { data: Chat[] };
    models: string[];
}>();
</script>

<template>
    <Head title="Chat" />

    <ChatWorkspace :chats="chats.data" :active-chat="null" :models="models" />
</template>
