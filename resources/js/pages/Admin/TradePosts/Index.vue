<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';

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
</script>

<template>
    <Head title="Post moderation" />

    <div class="mx-auto max-w-7xl p-6">
        <h1 class="mb-6 text-2xl font-semibold">
            Trade post moderation
        </h1>

        <div class="space-y-4">
            <div
                v-for="post in posts.data"
                :key="post.id"
                class="rounded border p-5"
            >
                <div class="flex flex-col justify-between gap-4 md:flex-row">
                    <div>
                        <strong>{{ post.title }}</strong>
                        <div class="text-sm text-gray-600">
                            {{ post.company.legal_name }}
                            · {{ typeLabel(post) }}
                            · {{ post.status }}
                        </div>

                        <div class="mt-2 text-sm">
                            <span v-if="post.price">
                                Price: {{ post.price }} {{ post.currency }}
                            </span>
                            <span v-if="post.price && post.target_price"> · </span>
                            <span v-if="post.target_price">
                                Target: {{ post.target_price }} {{ post.currency }}
                            </span>
                        </div>

                        <div
                            v-if="post.type === 'broker'"
                            class="mt-2 text-xs text-gray-500"
                        >
                            Broker/intermediary listing. Commission:
                            {{
                                post.broker_commission_type === 'negotiable'
                                    ? 'Negotiable'
                                    : `${post.broker_commission_value ?? ''} ${post.broker_commission_type === 'percentage' ? '%' : post.currency ?? ''}`
                            }}
                        </div>
                    </div>

                    <div class="space-x-2">
                        <button
                            class="rounded border px-3 py-2"
                            @click="decide(post.id, 'publish')"
                        >
                            Publish
                        </button>
                        <button
                            class="rounded border px-3 py-2"
                            @click="decide(post.id, 'reject')"
                        >
                            Reject
                        </button>
                        <button
                            class="rounded border px-3 py-2"
                            @click="decide(post.id, 'archive')"
                        >
                            Archive
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
