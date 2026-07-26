<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps<{ posts: any; categories: Array<{id:number; name:string}>; filters: any }>();
const filters = reactive({ q: props.filters.q ?? '', type: props.filters.type ?? '', category_id: props.filters.category_id ?? '' });
const search = () => router.get('/trade-posts', filters, { preserveState: true, replace: true });
</script>

<template>
    <Head title="Verified trade marketplace" />
    <div class="mx-auto max-w-7xl space-y-6 p-6">
        <div>
            <h1 class="text-3xl font-semibold">Verified import and export marketplace</h1>
            <p class="text-gray-600">Buy requests and sell offers from approved companies.</p>
        </div>
        <form class="grid gap-3 rounded-lg border p-4 md:grid-cols-4" @submit.prevent="search">
            <input v-model="filters.q" class="rounded border p-2" placeholder="Search products" />
            <select v-model="filters.type" class="rounded border p-2"><option value="">Buy and sell</option><option value="buy">Buy requests</option><option value="sell">Sell offers</option></select>
            <select v-model="filters.category_id" class="rounded border p-2"><option value="">All categories</option><option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option></select>
            <button class="rounded bg-black px-4 py-2 text-white">Search</button>
        </form>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <Link v-for="post in posts.data" :key="post.id" :href="`/trade-posts/${post.id}`" class="rounded-lg border p-5 hover:shadow">
                <div class="mb-2 flex justify-between"><span class="rounded-full border px-2 py-1 text-xs uppercase">{{ post.type }}</span><span class="text-xs">Verified company</span></div>
                <h2 class="font-semibold">{{ post.title }}</h2>
                <p class="text-sm text-gray-600">{{ post.company.trading_name || post.company.legal_name }}</p>
                <p class="mt-3 line-clamp-3 text-sm">{{ post.description }}</p>
            </Link>
        </div>
    </div>
</template>
