<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    BadgeCheck,
    ChevronLeft,
    Handshake,
    Package,
    ShieldCheck,
    Target,
} from '@lucide/vue';
import BrandHeader from '@/components/BrandHeader.vue';

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

    <div class="mx-auto max-w-6xl space-y-6 p-4 sm:p-6 lg:p-8">
        <div>
            <Link href="/trade-posts" class="inline-flex items-center gap-1.5 text-sm font-medium text-muted-foreground transition hover:text-foreground">
                <ChevronLeft class="size-4" /> Back to marketplace
            </Link>
        </div>

        <BrandHeader
            eyebrow="Trade opportunity"
            :title="post.title"
            :description="post.company.trading_name || post.company.legal_name"
        >
            <template #actions>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-semibold text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300">
                    <BadgeCheck class="size-4" /> Verified company
                </span>
            </template>
        </BrandHeader>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
            <main class="space-y-6">
                <section class="rounded-2xl border bg-card p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-full border bg-muted/40 px-3 py-1 text-xs font-semibold uppercase tracking-wide">
                            {{ postTypeLabel() }}
                        </span>
                        <span v-if="post.category?.name" class="rounded-full bg-muted px-3 py-1 text-xs font-medium text-muted-foreground">
                            {{ post.category.name }}
                        </span>
                    </div>

                    <div
                        v-if="post.type === 'broker'"
                        class="mt-5 flex gap-3 rounded-xl border border-violet-200 bg-violet-50 p-4 text-sm text-violet-950 dark:border-violet-900 dark:bg-violet-950/30 dark:text-violet-100"
                    >
                        <Handshake class="mt-0.5 size-5 shrink-0" />
                        <div>
                            <strong>Broker listing.</strong>
                            The posting company is acting as an intermediary and may not own or directly supply the listed goods.
                        </div>
                    </div>

                    <div v-if="post.images?.length" class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <img
                            v-for="image in post.images"
                            :key="image.id"
                            :src="`/storage/${image.path}`"
                            :alt="image.alt_text || post.title"
                            class="aspect-[4/3] w-full rounded-xl border object-cover"
                        />
                    </div>

                    <div class="mt-6">
                        <h2 class="text-lg font-semibold">Description</h2>
                        <p class="mt-3 whitespace-pre-line text-sm leading-7 text-muted-foreground sm:text-base">
                            {{ post.description }}
                        </p>
                    </div>
                </section>

                <section class="rounded-2xl border bg-card p-5 shadow-sm sm:p-6">
                    <h2 class="text-lg font-semibold">Product and logistics</h2>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-xl bg-muted/40 p-4">
                            <div class="flex items-center gap-2 text-xs font-medium uppercase tracking-wide text-muted-foreground">
                                <Package class="size-4" /> Product
                            </div>
                            <div class="mt-1 font-semibold">{{ post.product_name }}</div>
                        </div>
                        <div class="rounded-xl bg-muted/40 p-4">
                            <div class="text-xs font-medium uppercase tracking-wide text-muted-foreground">HS code</div>
                            <div class="mt-1 font-semibold">{{ post.hs_code || 'Not provided' }}</div>
                        </div>
                        <div class="rounded-xl bg-muted/40 p-4">
                            <div class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Origin</div>
                            <div class="mt-1 font-semibold">{{ post.origin_country_code || 'Open / not provided' }}</div>
                        </div>
                        <div class="rounded-xl bg-muted/40 p-4">
                            <div class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Destination</div>
                            <div class="mt-1 font-semibold">{{ post.destination_country_code || 'Open / not provided' }}</div>
                        </div>
                        <div class="rounded-xl bg-muted/40 p-4">
                            <div class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Incoterm</div>
                            <div class="mt-1 font-semibold">{{ post.incoterm || 'Not provided' }}</div>
                        </div>
                        <div class="rounded-xl bg-muted/40 p-4">
                            <div class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Minimum order</div>
                            <div class="mt-1 font-semibold">{{ post.minimum_order_quantity || 'Not provided' }}</div>
                        </div>
                    </div>
                </section>
            </main>

            <aside class="space-y-4 lg:sticky lg:top-6 lg:self-start">
                <section class="rounded-2xl border bg-card p-5 shadow-sm">
                    <h2 class="font-semibold">Commercial summary</h2>

                    <div class="mt-4 space-y-4">
                        <div>
                            <div class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Quantity</div>
                            <div class="mt-1 text-xl font-bold">
                                {{ post.quantity ? `${post.quantity} ${post.unit ?? ''}` : 'Open' }}
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Price</div>
                            <div class="mt-1 font-semibold">
                                {{ post.price ? `${post.price} ${post.currency ?? ''}` : 'Negotiable / not provided' }}
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex items-center gap-1.5 text-xs font-medium uppercase tracking-wide text-muted-foreground">
                                <Target class="size-4" /> Target price
                            </div>
                            <div class="mt-1 text-xl font-bold text-primary">
                                {{ post.target_price ? `${post.target_price} ${post.currency ?? ''}` : 'Not provided' }}
                            </div>
                        </div>

                        <template v-if="post.type === 'broker'">
                            <div class="border-t pt-4">
                                <div class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Broker direction</div>
                                <div class="mt-1 font-semibold">
                                    {{ post.broker_direction === 'seeking_seller' ? 'Seeking seller' : 'Seeking buyer' }}
                                </div>
                            </div>
                            <div class="border-t pt-4">
                                <div class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Broker commission</div>
                                <div class="mt-1 font-semibold">{{ commissionLabel() }}</div>
                            </div>
                        </template>
                    </div>
                </section>

                <section class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-emerald-950 dark:border-emerald-900 dark:bg-emerald-950/30 dark:text-emerald-100">
                    <div class="flex items-start gap-3">
                        <ShieldCheck class="mt-0.5 size-5 shrink-0" />
                        <div>
                            <h3 class="font-semibold">Verified company</h3>
                            <p class="mt-1 text-sm opacity-80">
                                Company information has passed platform review. Always confirm commercial terms before entering a transaction.
                            </p>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</template>
