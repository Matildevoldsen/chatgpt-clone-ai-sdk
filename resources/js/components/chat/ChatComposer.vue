<script setup lang="ts">
import { SendHorizontal } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

defineProps<{
    models: string[];
    disabled: boolean;
}>();

const model = defineModel<string>('model', { required: true });
const message = defineModel<string>('message', { required: true });

const emit = defineEmits<{
    submit: [];
}>();
</script>

<template>
    <form
        class="border-t bg-background/95 px-4 py-4 backdrop-blur"
        @submit.prevent="emit('submit')"
    >
        <div class="flex w-full gap-3">
            <div
                class="flex flex-1 items-end gap-2 rounded-lg border bg-background p-2 shadow-sm"
            >
                <textarea
                    v-model="message"
                    rows="1"
                    class="max-h-40 min-h-11 flex-1 resize-none bg-transparent px-2 py-3 text-sm outline-none"
                    placeholder="Message ChatGPT"
                    :disabled="disabled"
                    @keydown.enter.exact.prevent="emit('submit')"
                />
                <Select v-model="model" :disabled="disabled">
                    <SelectTrigger class="h-9 w-36">
                        <SelectValue placeholder="Model" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="option in models"
                            :key="option"
                            :value="option"
                        >
                            {{ option }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <Button
                    size="icon"
                    class="size-9"
                    :disabled="disabled || message.trim().length === 0"
                >
                    <SendHorizontal class="size-4" />
                </Button>
            </div>
        </div>
    </form>
</template>
