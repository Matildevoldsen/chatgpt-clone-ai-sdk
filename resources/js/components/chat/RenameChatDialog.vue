<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import type { Chat } from '@/types';

const open = defineModel<boolean>('open', { required: true });
const props = defineProps<{ chat: Chat | null }>();
const title = ref('');

watch(() => props.chat, (chat) => {
    title.value = chat?.title ?? '';
});

const save = () => {
    if (!props.chat || title.value.trim().length === 0) {
        return;
    }

    router.patch(`/chat/${props.chat.id}`, { title: title.value }, {
        preserveScroll: true,
        onSuccess: () => {
            open.value = false;
        },
    });
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Rename chat</DialogTitle>
            </DialogHeader>
            <Input v-model="title" autofocus @keydown.enter="save" />
            <DialogFooter>
                <Button variant="outline" @click="open = false">Cancel</Button>
                <Button @click="save">Save</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
