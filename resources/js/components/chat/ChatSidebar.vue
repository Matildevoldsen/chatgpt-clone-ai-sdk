<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { LogOut, MessageSquare, Pencil, Plus, Trash2 } from '@lucide/vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupContent,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuAction,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarRail,
} from '@/components/ui/sidebar';
import type { Chat } from '@/types';

const props = defineProps<{
    chats: Chat[];
    activeChatId: string | null;
}>();

const emit = defineEmits<{
    rename: [chat: Chat];
}>();

const deleteChat = (chat: Chat) => {
    if (!window.confirm(`Delete "${chat.title}"?`)) {
        return;
    }

    router.delete(`/chat/${chat.id}`, { preserveScroll: true });
};
</script>

<template>
    <Sidebar collapsible="offcanvas" variant="sidebar">
        <SidebarHeader class="border-b">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link href="/chat">
                            <MessageSquare class="size-4" />
                            <span class="font-semibold">ChatGPT</span>
                        </Link>
                    </SidebarMenuButton>
                    <SidebarMenuAction as-child>
                        <Link href="/chat" aria-label="New chat">
                            <Plus />
                        </Link>
                    </SidebarMenuAction>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <SidebarGroup>
                <SidebarGroupContent>
                    <p
                        v-if="props.chats.length === 0"
                        class="px-2 py-8 text-sm text-sidebar-foreground/60"
                    >
                        No chats yet.
                    </p>

                    <SidebarMenu>
                        <SidebarMenuItem
                            v-for="chat in props.chats"
                            :key="chat.id"
                        >
                            <SidebarMenuButton
                                as-child
                                :is-active="chat.id === props.activeChatId"
                            >
                                <Link :href="`/chat/${chat.id}`">
                                    <MessageSquare />
                                    <span>{{ chat.title }}</span>
                                </Link>
                            </SidebarMenuButton>

                            <SidebarMenuAction
                                show-on-hover
                                aria-label="Rename chat"
                                @click="emit('rename', chat)"
                            >
                                <Pencil />
                            </SidebarMenuAction>
                            <SidebarMenuAction
                                show-on-hover
                                aria-label="Delete chat"
                                class="right-7"
                                @click="deleteChat(chat)"
                            >
                                <Trash2 />
                            </SidebarMenuAction>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroupContent>
            </SidebarGroup>
        </SidebarContent>

        <SidebarFooter class="border-t">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton as-child>
                        <Link href="/logout" method="post" as="button">
                            <LogOut />
                            <span>Logout</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarFooter>
        <SidebarRail />
    </Sidebar>
</template>
