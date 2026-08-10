<script setup lang="ts">
defineProps<{
    eyebrow?: string;
    title: string;
    description?: string;
}>();
</script>

<template>
    <section class="relative overflow-hidden rounded-3xl border bg-card p-6 shadow-sm sm:p-8">
        <div class="pointer-events-none absolute -right-16 -top-20 size-56 rounded-full bg-blue-500/10 blur-3xl" />
        <div class="pointer-events-none absolute -bottom-20 left-1/3 size-48 rounded-full bg-emerald-500/10 blur-3xl" />

        <div class="relative flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0">
                <div class="mb-4 h-14 w-52 overflow-hidden" aria-label="Tradeify">
                    <img
                        src="/brand/tradeify-logo.png"
                        alt="Tradeify"
                        class="relative left-0 top-1/2 h-auto w-full -translate-y-1/2"
                    />
                </div>

                <p v-if="eyebrow" class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400">
                    {{ eyebrow }}
                </p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight sm:text-4xl">
                    {{ title }}
                </h1>
                <p v-if="description" class="mt-3 max-w-3xl text-sm leading-6 text-muted-foreground sm:text-base">
                    {{ description }}
                </p>
            </div>

            <div v-if="$slots.actions" class="flex shrink-0 flex-wrap gap-3">
                <slot name="actions" />
            </div>
        </div>
    </section>
</template>
