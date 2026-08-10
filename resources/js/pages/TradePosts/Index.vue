<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import {
    BadgeCheck,
    Handshake,
    PackageSearch,
    Plus,
    Search,
    ShoppingCart,
    Store,
    Target,
} from '@lucide/vue';
import { computed, reactive } from 'vue';
import BrandHeader from '@/components/BrandHeader.vue';

const props = defineProps<{
    posts: any;
    categories: Array<{ id: number; name: string }>;
    filters: any;
}>();

const filters = reactive({
    q: props.filters.q ?? '',
    type: props.filters.type ?? '',
    category_id: props.filters.category_id ?? '',
});

const search = () =>
    router.get('/trade-posts', filters, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });

const clearFilters = () => {
    filters.q = '';
    filters.type = '';
    filters.category_id = '';
    search();
};

const hasFilters = computed(() =>
    Boolean(filters.q || filters.type || filters.category_id),
);

const resultCount = computed(() =>
    props.posts.total ?? props.posts.data?.length ?? 0,
);

const postTypeLabel = (post: any) => {
    if (post.type === 'buy') return 'Buy request';
    if (post.type === 'sell') return 'Sell offer';

    return post.broker_direction === 'seeking_seller'
        ? 'Broker · Seeking seller'
        : 'Broker · Seeking buyer';
};

const typeClasses = (post: any) => {
    if (post.type === 'buy') return 'border-sky-200 bg-sky-50 text-sky-700 dark:border-sky-900 dark:bg-sky-950/40 dark:text-sky-300';
    if (post.type === 'sell') return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300';

    return 'border-violet-200 bg-violet-50 text-violet-700 dark:border-violet-900 dark:bg-violet-950/40 dark:text-violet-300';
};
</script>

<template>
    <Head title="Verified trade marketplace" />

    <div class="mx-auto max-w-7xl space-y-6 p-4 sm:p-6 lg:p-8">
        <BrandHeader
            eyebrow="Verified marketplace"
            title="Find serious trade opportunities"
            description="Browse buy requests, sell offers and broker opportunities from approved companies. Use filters to get to the right deal faster."
        >
            <template #actions>
                <Link
                    href="/my-posts"
                    class="inline-flex items-center rounded-xl border bg-background px-4 py-2.5 text-sm font-semibold transition hover:bg-muted"
                >
                    My posts
                </Link>
                <Link
                    href="/trade-posts/create"
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground shadow-sm transition hover:opacity-90"
                >
                    <Plus class="size-4" />
                    Create opportunity
                </Link>
            </template>
        </BrandHeader>

        <section class="rounded-2xl border bg-card p-4 shadow-sm sm:p-5">
            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold">Search the marketplace</h2>
                    <p class="text-sm text-muted-foreground">
                        {{ resultCount }} {{ resultCount === 1 ? 'opportunity' : 'opportunities' }} found
                    </p>
                </div>
                <button
                    v-if="hasFilters"
                    type="button"
                    class="self-start text-sm font-medium text-primary hover:underline"
                    @click="clearFilters"
                >
                    Clear filters
                </button>
            </div>

            <form class="grid gap-3 lg:grid-cols-[2fr_1fr_1fr_auto]" @submit.prevent="search">
                <label class="relative">
                    <Search class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                    <input
                        v-model="filters.q"
                        class="h-11 w-full rounded-xl border bg-background pl-10 pr-3 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                        placeholder="Search product, title or keyword"
                    />
                </label>

                <select v-model="filters.type" class="h-11 rounded-xl border bg-background px-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/15">
                    <option value="">All opportunity types</option>
                    <option value="buy">Buy requests</option>
                    <option value="sell">Sell offers</option>
                    <option value="broker">Broker opportunities</option>
                </select>

                <select v-model="filters.category_id" class="h-11 rounded-xl border bg-background px-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/15">
                    <option value="">All categories</option>
                    <option v-for="c in categories" :key="c.id" :value="c.id">
                        {{ c.name }}
                    </option>
                </select>

                <button class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-foreground px-5 text-sm font-semibold text-background transition hover:opacity-90">
                    <Search class="size-4" />
                    Search
                </button>
            </form>

            <div class="mt-4 flex flex-wrap gap-2">
                <button type="button" class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-medium transition hover:bg-muted" @click="filters.type = 'buy'; search()">
                    <ShoppingCart class="size-3.5" /> Buy requests
                </button>
                <button type="button" class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-medium transition hover:bg-muted" @click="filters.type = 'sell'; search()">
                    <Store class="size-3.5" /> Sell offers
                </button>
                <button type="button" class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-medium transition hover:bg-muted" @click="filters.type = 'broker'; search()">
                    <Handshake class="size-3.5" /> Broker opportunities
                </button>
            </div>
        </section>

        <div v-if="posts.data?.length" class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <Link
                v-for="post in posts.data"
                :key="post.id"
                :href="`/trade-posts/${post.id}`"
                class="group flex min-h-72 flex-col rounded-2xl border bg-card p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-md"
            >
                <div class="mb-4 flex items-start justify-between gap-3">
                    <span class="rounded-full border px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide" :class="typeClasses(post)">
                        {{ postTypeLabel(post) }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 dark:text-emerald-400">
                        <BadgeCheck class="size-4" /> Verified
                    </span>
                </div>

                <div class="min-w-0">
                    <p v-if="post.category?.name" class="text-xs font-medium uppercase tracking-wide text-muted-foreground">
                        {{ post.category.name }}
                    </p>
                    <h2 class="mt-1 line-clamp-2 text-lg font-bold leading-snug group-hover:text-primary">
                        {{ post.title }}
                    </h2>
                    <p class="mt-1 truncate text-sm text-muted-foreground">
                        {{ post.company.trading_name || post.company.legal_name }}
                    </p>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-2 text-sm">
                    <div class="rounded-xl bg-muted/50 p-3">
                        <div class="text-xs text-muted-foreground">Quantity</div>
                        <div class="mt-0.5 font-semibold">
                            {{ post.quantity ? `${post.quantity} ${post.unit ?? ''}` : 'Open' }}
                        </div>
                    </div>
                    <div class="rounded-xl bg-muted/50 p-3">
                        <div class="flex items-center gap-1 text-xs text-muted-foreground">
                            <Target class="size-3.5" /> Target price
                        </div>
                        <div class="mt-0.5 font-semibold">
                            {{ post.target_price ? `${post.target_price} ${post.currency ?? ''}` : post.price ? `${post.price} ${post.currency ?? ''}` : 'Negotiable' }}
                        </div>
                    </div>
                </div>

                <p class="mt-4 line-clamp-3 flex-1 text-sm leading-6 text-muted-foreground">
                    {{ post.description }}
                </p>

                <div class="mt-5 flex items-center justify-between border-t pt-4 text-sm">
                    <span class="font-semibold text-primary">View opportunity</span>
                    <span class="text-muted-foreground transition group-hover:translate-x-1">→</span>
                </div>
            </Link>
        </div>

        <section v-else class="rounded-2xl border border-dashed bg-card p-10 text-center sm:p-14">
            <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-muted">
                <PackageSearch class="size-7 text-muted-foreground" />
            </div>
            <h2 class="mt-4 text-xl font-semibold">No opportunities found</h2>
            <p class="mx-auto mt-2 max-w-md text-sm text-muted-foreground">
                Try a broader keyword or clear the filters. You can also create a new opportunity for verified companies to discover.
            </p>
            <div class="mt-5 flex flex-wrap justify-center gap-3">
                <button v-if="hasFilters" type="button" class="rounded-xl border px-4 py-2 text-sm font-medium hover:bg-muted" @click="clearFilters">
                    Clear filters
                </button>
                <Link href="/trade-posts/create" class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground">
                    <Plus class="size-4" /> Create opportunity
                </Link>
            </div>
        </section>

        <nav v-if="posts.links?.length > 3" class="flex flex-wrap justify-center gap-2" aria-label="Marketplace pages">
            <template v-for="link in posts.links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    class="rounded-lg border px-3 py-2 text-sm transition hover:bg-muted"
                    :class="link.active ? 'border-primary bg-primary text-primary-foreground hover:bg-primary' : 'bg-card'"
                    v-html="link.label"
                />
                <span v-else class="rounded-lg border px-3 py-2 text-sm text-muted-foreground opacity-50" v-html="link.label" />
            </template>
        </nav>
    </div>
</template>
