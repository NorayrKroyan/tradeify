<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
defineProps<{ posts: any }>();
const decide = (id:number, decision:string) => router.post(`/admin/trade-posts/${id}/decision`, { decision, admin_notes: '' });
</script>
<template><Head title="Post moderation"/><div class="mx-auto max-w-7xl p-6"><h1 class="mb-6 text-2xl font-semibold">Trade post moderation</h1><div class="space-y-4"><div v-for="post in posts.data" :key="post.id" class="rounded border p-5"><div class="flex justify-between"><div><strong>{{ post.title }}</strong><div class="text-sm text-gray-600">{{ post.company.legal_name }} · {{ post.status }}</div></div><div class="space-x-2"><button class="rounded border px-3 py-2" @click="decide(post.id,'publish')">Publish</button><button class="rounded border px-3 py-2" @click="decide(post.id,'reject')">Reject</button><button class="rounded border px-3 py-2" @click="decide(post.id,'archive')">Archive</button></div></div></div></div></div></template>
