<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Clock3, FileText, Plus, Send, ShieldCheck } from '@lucide/vue';
import { computed } from 'vue';
import BrandHeader from '@/components/BrandHeader.vue';

const props = defineProps<{
    posts: any;
}>();

const typeLabel = (post: any) => {
    if (post.type === 'buy') return 'Buy request';
    if (post.type === 'sell') return 'Sell offer';

    return post.broker_direction === 'seeking_seller'
        ? 'Broker · Seeking seller'
        : 'Broker · Seeking buyer';
};

const statusLabel = (status: string) =>
    status.replaceAll('_', ' ').replace(/\b\w/g, (char) => char.toUpperCase());

const statusClasses = (status: string) => {
    if (['published', 'approved', 'active'].includes(status)) {
        return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300';
    }

    if (['rejected', 'archived'].includes(status)) {
        return 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900 dark:bg-rose-950/40 dark:text-rose-300';
    }

    return 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-900 dark:bg-amber-950/40 dark:text-amber-300';
};

const total = computed(() => props.posts.total ?? props.posts.data?.length ?? 0);
const published = computed(() =>
    (props.posts.data ?? []).filter((post: any) => ['published', 'approved', 'active'].includes(post.status)).length,
);
const pending = computed(() =>
    (props.posts.data ?? []).filter((post: any) => ['pending', 'under_review', 'submitted'].includes(post.status)).length,
);
</script>

<template>
    <Head title="My posts" />

    <div class="mx-auto max-w-6xl space-y-6 p-4 sm:p-6 lg:p-8">
        <BrandHeader
            eyebrow="Your marketplace activity"
            title="My trade posts"
            description="Track what you have submitted, see moderation status and create your next opportunity."
        >
            <template #actions>
                <Link
                    href="/trade-posts"
                    class="inline-flex items-center rounded-xl border bg-background px-4 py-2.5 text-sm font-semibold transition hover:bg-muted"
                >
                    Browse marketplace
                </Link>
                <Link
                    href="/trade-posts/create"
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground"
                >
                    <Plus class="size-4" />
                    Create post
                </Link>
            </template>
        </BrandHeader>

        <section class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-2xl border bg-card p-4 shadow-sm">
                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                    <FileText class="size-4" /> Total posts
                </div>
                <div class="mt-2 text-2xl font-bold">{{ total }}</div>
            </div>
            <div class="rounded-2xl border bg-card p-4 shadow-sm">
                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                    <ShieldCheck class="size-4" /> Published
                </div>
                <div class="mt-2 text-2xl font-bold">{{ published }}</div>
            </div>
            <div class="rounded-2xl border bg-card p-4 shadow-sm">
                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                    <Clock3 class="size-4" /> In review
                </div>
                <div class="mt-2 text-2xl font-bold">{{ pending }}</div>
            </div>
        </section>

        <section v-if="posts.data?.length" class="space-y-3">
            <Link
                v-for="post in posts.data"
                :key="post.id"
                :href="`/trade-posts/${post.id}`"
                class="group block rounded-2xl border bg-card p-5 shadow-sm transition hover:border-primary/40 hover:shadow-md"
            >
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full border bg-muted/40 px-2.5 py-1 text-xs font-semibold">
                                {{ typeLabel(post) }}
                            </span>
                            <span
                                class="rounded-full border px-2.5 py-1 text-xs font-semibold"
                                :class="statusClasses(post.status)"
                            >
                                {{ statusLabel(post.status) }}
                            </span>
                        </div>

                        <h2 class="mt-3 text-lg font-bold group-hover:text-primary">
                            {{ post.title }}
                        </h2>
                        <p class="mt-1 text-sm text-muted-foreground">
                            {{ post.category?.name || 'Uncategorized' }}
                        </p>

                        <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-sm">
                            <span v-if="post.quantity">
                                <span class="text-muted-foreground">Quantity:</span>
                                <strong>{{ post.quantity }} {{ post.unit }}</strong>
                            </span>
                            <span v-if="post.target_price">
                                <span class="text-muted-foreground">Target:</span>
                                <strong>{{ post.target_price }} {{ post.currency }}</strong>
                            </span>
                            <span v-else-if="post.price">
                                <span class="text-muted-foreground">Price:</span>
                                <strong>{{ post.price }} {{ post.currency }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 text-sm font-semibold text-primary">
                        View details <span class="transition group-hover:translate-x-1">→</span>
                    </div>
                </div>
            </Link>
        </section>

        <section v-else class="rounded-2xl border border-dashed bg-card p-10 text-center sm:p-14">
            <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-muted">
                <Send class="size-7 text-muted-foreground" />
            </div>
            <h2 class="mt-4 text-xl font-semibold">No trade posts yet</h2>
            <p class="mx-auto mt-2 max-w-md text-sm text-muted-foreground">
                Create your first buy request, sell offer or broker opportunity and submit it for review.
            </p>
            <Link href="/trade-posts/create" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground">
                <Plus class="size-4" /> Create first post
            </Link>
        </section>
    </div>
</template>
