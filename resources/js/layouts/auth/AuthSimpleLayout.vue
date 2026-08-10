<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { BadgeCheck, ShieldCheck } from '@lucide/vue';
import { home } from '@/routes';

const props = defineProps<{
    title?: string;
    description?: string;
}>();

const isWide = computed(() => {
    const title = (props.title ?? '').toLowerCase();

    return title.includes('join') || title.includes('create account') || title.includes('register');
});
</script>

<template>
    <div class="relative flex min-h-svh items-center justify-center overflow-hidden bg-background p-4 sm:p-6 md:p-10">
        <div class="pointer-events-none absolute -left-24 top-0 size-80 rounded-full bg-blue-500/10 blur-3xl" />
        <div class="pointer-events-none absolute -right-24 bottom-0 size-80 rounded-full bg-emerald-500/10 blur-3xl" />

        <div class="relative w-full" :class="isWide ? 'max-w-5xl' : 'max-w-lg'">
            <div class="rounded-3xl border bg-card/95 p-5 shadow-xl shadow-blue-500/5 backdrop-blur sm:p-8">
                <div class="flex flex-col items-center gap-5">
                    <Link :href="home()" class="block h-16 w-56 overflow-hidden" aria-label="Tradeify home">
                        <img
                            src="/brand/tradeify-logo.png"
                            alt="Tradeify"
                            class="relative top-1/2 h-auto w-full -translate-y-1/2"
                        />
                    </Link>

                    <div class="space-y-2 text-center">
                        <h1 class="text-2xl font-bold tracking-tight">{{ title }}</h1>
                        <p v-if="description" class="mx-auto max-w-2xl text-sm leading-6 text-muted-foreground">
                            {{ description }}
                        </p>
                    </div>

                    <div class="flex flex-wrap justify-center gap-x-4 gap-y-2 text-xs font-medium text-muted-foreground">
                        <span class="inline-flex items-center gap-1.5"><ShieldCheck class="size-3.5 text-emerald-600" /> Secure account flow</span>
                        <span class="inline-flex items-center gap-1.5"><BadgeCheck class="size-3.5 text-blue-600" /> Verified marketplace</span>
                    </div>
                </div>

                <div class="mt-7">
                    <slot />
                </div>
            </div>

            <p class="mt-5 text-center text-xs text-muted-foreground">
                Tradeify · Verified B2B trade for buyers, sellers and brokers
            </p>
        </div>
    </div>
</template>
