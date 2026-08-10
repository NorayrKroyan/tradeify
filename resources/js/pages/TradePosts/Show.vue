<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    post: any;
}>();

const postTypeLabel = () => {
    if (props.post.type === 'buy') return 'Buy request';
    if (props.post.type === 'sell') return 'Sell offer';

    return props.post.broker_direction === 'seeking_seller'
        ? 'Broker opportunity · Seeking seller'
        : 'Broker opportunity · Seeking buyer';
};

const commissionLabel = () => {
    if (props.post.type !== 'broker') return null;

    if (props.post.broker_commission_type === 'negotiable') {
        return 'Negotiable';
    }

    if (props.post.broker_commission_type === 'percentage') {
        return `${props.post.broker_commission_value}%`;
    }

    if (props.post.broker_commission_type === 'fixed') {
        return `${props.post.broker_commission_value} ${props.post.currency ?? ''}`.trim();
    }

    return 'Not provided';
};
</script>

<template>
    <Head :title="post.title" />

    <div class="mx-auto max-w-4xl space-y-5 p-6">
        <div>
            <span class="rounded-full border px-3 py-1 text-sm uppercase">
                {{ postTypeLabel() }}
            </span>
            <h1 class="mt-3 text-3xl font-semibold">
                {{ post.title }}
            </h1>
            <p class="text-gray-600">
                {{ post.company.trading_name || post.company.legal_name }} · Verified
            </p>
        </div>

        <div
            v-if="post.type === 'broker'"
            class="rounded-lg border bg-gray-50 p-4 text-sm"
        >
            <strong>Broker listing.</strong>
            The posting company is acting as an intermediary and may not own or directly supply the listed goods.
        </div>

        <div class="grid gap-3 md:grid-cols-3">
            <img
                v-for="image in post.images"
                :key="image.id"
                :src="`/storage/${image.path}`"
                :alt="image.alt_text"
                class="aspect-square w-full rounded object-cover"
            />
        </div>

        <p class="whitespace-pre-line">
            {{ post.description }}
        </p>

        <div class="grid gap-3 rounded border p-5 md:grid-cols-2">
            <div>Product: {{ post.product_name }}</div>
            <div>HS code: {{ post.hs_code || 'Not provided' }}</div>
            <div>Quantity: {{ post.quantity }} {{ post.unit }}</div>
            <div>
                Price:
                {{ post.price ? `${post.price} ${post.currency ?? ''}` : 'Not provided' }}
            </div>
            <div>
                Target price:
                {{ post.target_price ? `${post.target_price} ${post.currency ?? ''}` : 'Not provided' }}
            </div>
            <div>Incoterm: {{ post.incoterm || 'Not provided' }}</div>
            <div>Origin: {{ post.origin_country_code || 'Not provided' }}</div>
            <div>Destination: {{ post.destination_country_code || 'Not provided' }}</div>

            <template v-if="post.type === 'broker'">
                <div>
                    Broker direction:
                    {{
                        post.broker_direction === 'seeking_seller'
                            ? 'Seeking seller'
                            : 'Seeking buyer'
                    }}
                </div>
                <div>
                    Broker commission: {{ commissionLabel() }}
                </div>
            </template>
        </div>
    </div>
</template>
