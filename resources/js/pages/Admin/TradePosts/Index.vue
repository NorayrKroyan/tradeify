<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Archive, CheckCircle2, Clock3, ShieldCheck, XCircle } from '@lucide/vue';
import BrandHeader from '@/components/BrandHeader.vue';

defineProps<{
    posts: any;
}>();

const decide = (id: number, decision: string) =>
    router.post(`/admin/trade-posts/${id}/decision`, {
        decision,
        admin_notes: '',
    });

const typeLabel = (post: any) => {
    if (post.type === 'buy') return 'Buy request';
    if (post.type === 'sell') return 'Sell offer';

    return post.broker_direction === 'seeking_seller'
        ? 'Broker · Seeking seller'
        : 'Broker · Seeking buyer';
};

const statusLabel = (status: string) =>
    status.replaceAll('_', ' ').replace(/\b\w/g, (char) => char.toUpperCase());
</script>

<template>
    <Head title="Post moderation" />

    <div class="mx-auto max-w-7xl space-y-6 p-4 sm:p-6 lg:p-8">
        <BrandHeader
            eyebrow="Administrator workspace"
            title="Trade post moderation"
            description="Review commercial details, verify that the listing matches the company's approved role, and publish only clear marketplace opportunities."
        >
            <template #actions>
                <Link href="/admin/verifications" class="rounded-xl border bg-background px-4 py-2.5 text-sm font-semibold hover:bg-muted">
                    Company reviews
                </Link>
                <Link href="/trade-posts" class="rounded-xl border bg-background px-4 py-2.5 text-sm font-semibold hover:bg-muted">
                    View marketplace
                </Link>
            </template>
        </BrandHeader>

        <section v-if="posts.data?.length" class="space-y-4">
            <article
                v-for="post in posts.data"
                :key="post.id"
                class="rounded-2xl border bg-card p-5 shadow-sm sm:p-6"
            >
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full border bg-muted/50 px-2.5 py-1 text-xs font-semibold">
                                {{ typeLabel(post) }}
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs font-semibold text-muted-foreground">
                                <Clock3 class="size-3.5" /> {{ statusLabel(post.status) }}
                            </span>
                        </div>

                        <h2 class="mt-3 text-lg font-bold">{{ post.title }}</h2>
                        <div class="mt-1 flex items-center gap-1.5 text-sm text-muted-foreground">
                            <ShieldCheck class="size-4" /> {{ post.company.legal_name }}
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2 text-sm">
                            <span v-if="post.price" class="rounded-lg bg-muted/50 px-3 py-2">
                                <span class="text-muted-foreground">Price:</span>
                                <strong>{{ post.price }} {{ post.currency }}</strong>
                            </span>
                            <span v-if="post.target_price" class="rounded-lg bg-muted/50 px-3 py-2">
                                <span class="text-muted-foreground">Target:</span>
                                <strong>{{ post.target_price }} {{ post.currency }}</strong>
                            </span>
                            <span v-if="post.quantity" class="rounded-lg bg-muted/50 px-3 py-2">
                                <span class="text-muted-foreground">Quantity:</span>
                                <strong>{{ post.quantity }} {{ post.unit }}</strong>
                            </span>
                        </div>

                        <div v-if="post.type === 'broker'" class="mt-4 rounded-xl border border-violet-200 bg-violet-50 p-3 text-sm text-violet-950 dark:border-violet-900 dark:bg-violet-950/30 dark:text-violet-100">
                            Broker/intermediary listing · Commission:
                            <strong>
                                {{
                                    post.broker_commission_type === 'negotiable'
                                        ? 'Negotiable'
                                        : `${post.broker_commission_value ?? ''} ${post.broker_commission_type === 'percentage' ? '%' : post.currency ?? ''}`
                                }}
                            </strong>
                        </div>
                    </div>

                    <div class="grid shrink-0 grid-cols-3 gap-2 lg:grid-cols-1">
                        <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700" @click="decide(post.id, 'publish')">
                            <CheckCircle2 class="size-4" /> Publish
                        </button>
                        <button class="inline-flex items-center justify-center gap-2 rounded-xl border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-50 dark:border-rose-900 dark:text-rose-300 dark:hover:bg-rose-950/30" @click="decide(post.id, 'reject')">
                            <XCircle class="size-4" /> Reject
                        </button>
                        <button class="inline-flex items-center justify-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-semibold hover:bg-muted" @click="decide(post.id, 'archive')">
                            <Archive class="size-4" /> Archive
                        </button>
                    </div>
                </div>
            </article>
        </section>

        <section v-else class="rounded-2xl border border-dashed bg-card p-12 text-center">
            <CheckCircle2 class="mx-auto size-10 text-emerald-600" />
            <h2 class="mt-4 text-xl font-semibold">Moderation queue is clear</h2>
            <p class="mt-2 text-sm text-muted-foreground">There are no trade posts waiting for a decision.</p>
        </section>
    </div>
</template>
