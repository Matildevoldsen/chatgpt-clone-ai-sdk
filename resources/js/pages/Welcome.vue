<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { MessageSquare } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { login, register } from '@/routes';

defineProps<{
    auth?: {
        user?: unknown;
    };
}>();
</script>

<template>
    <Head title="Laravel Meetup India" />

    <main class="flex min-h-dvh items-center justify-center bg-zinc-950 px-6 text-white">
        <section class="w-full max-w-2xl text-center">
            <div class="mx-auto mb-6 flex size-12 items-center justify-center rounded-lg bg-emerald-500">
                <MessageSquare class="size-6" />
            </div>

            <h1 class="text-4xl font-semibold tracking-normal md:text-5xl">
                ChatGPT
            </h1>
            <p class="mx-auto mt-4 max-w-xl text-base leading-7 text-zinc-300">
                A compact Laravel AI SDK demo with Azure GPT streaming, Vue, saved history,
                and shadcn-vue components.
            </p>

            <div class="mt-8 flex justify-center gap-3">
                <Button v-if="auth?.user" as-child>
                    <Link href="/chat">Open chat</Link>
                </Button>
                <template v-else>
                    <Button as-child>
                        <Link :href="login()">Log in</Link>
                    </Button>
                    <Button as-child variant="outline" class="border-white/20 bg-transparent text-white hover:bg-white/10">
                        <Link :href="register()">Register</Link>
                    </Button>
                </template>
            </div>
        </section>
    </main>
</template>
