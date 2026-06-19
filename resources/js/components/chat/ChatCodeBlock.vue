<script setup lang="ts">
import { Check, Copy } from '@lucide/vue';
import {
    loadRegisteredShikiLanguage,
    useShikiHighlighter,
} from 'streamdown-vue';
import { computed, onMounted, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';

const props = withDefaults(
    defineProps<{
        code: string;
        language?: string;
        hideCopy?: boolean;
    }>(),
    {
        language: '',
        hideCopy: false,
    },
);

const copied = ref(false);
const highlightedCode = ref('');

const languageLabel = computed(() => props.language.trim().toUpperCase());
const highlightLanguage = computed(() => {
    const language = props.language.trim().toLowerCase();

    return language === 'blade' ? 'php' : language || 'text';
});

const escapedCode = computed(() =>
    props.code
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;'),
);

const renderHighlightedCode = async () => {
    try {
        const highlighter = await useShikiHighlighter();
        const language = highlightLanguage.value;

        if (!highlighter.getLoadedLanguages().includes(language)) {
            const loaded = await loadRegisteredShikiLanguage(language);

            if (!loaded) {
                highlightedCode.value = '';

                return;
            }
        }

        highlightedCode.value = highlighter
            .codeToHtml(props.code, {
                lang: language,
                theme: 'github-dark',
            })
            .replace(/<pre([^>]*) style="[^"]*"/, '<pre$1');
    } catch {
        highlightedCode.value = '';
    }
};

const copyCode = async () => {
    if (props.code.length === 0) {
        return;
    }

    await navigator.clipboard.writeText(props.code);
    copied.value = true;

    window.setTimeout(() => {
        copied.value = false;
    }, 2000);
};

onMounted(renderHighlightedCode);

watch(() => [props.code, props.language], renderHighlightedCode);
</script>

<template>
    <div
        class="w-full max-w-none overflow-hidden rounded-md border bg-gray-50 dark:bg-gray-800"
        data-streamdown="code-block"
    >
        <div
            class="flex min-h-8 items-center justify-between gap-2 border-b bg-gray-100 px-2 py-1 text-xs dark:border-gray-700 dark:bg-gray-900"
            data-streamdown="code-block-header"
        >
            <span
                v-if="languageLabel"
                class="rounded bg-gray-200 px-1 py-0.5 font-mono text-[10px] leading-none tracking-normal dark:bg-gray-700"
                data-streamdown="code-lang"
            >
                {{ languageLabel }}
            </span>
            <span v-else class="h-4" data-streamdown="code-lang-empty" />

            <Button
                v-if="!hideCopy"
                type="button"
                variant="ghost"
                size="icon"
                class="size-6"
                aria-label="Copy code"
                @click="copyCode"
            >
                <Check v-if="copied" class="size-3.5" />
                <Copy v-else class="size-3.5" />
            </Button>
        </div>

        <div
            class="w-full max-w-full overflow-x-auto p-0"
            data-streamdown="code-body"
        >
            <div
                v-if="highlightedCode"
                class="chat-code-highlight w-full max-w-full overflow-x-auto"
                v-html="highlightedCode"
            />
            <pre
                v-else
                class="w-full max-w-full overflow-x-auto font-mono text-sm leading-7"
                data-streamdown="pre"
            ><code data-streamdown="code" v-html="escapedCode" /></pre>
        </div>
    </div>
</template>
