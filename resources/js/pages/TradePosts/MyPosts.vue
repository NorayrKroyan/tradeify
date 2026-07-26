<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
defineProps<{ posts: any }>();
</script>
<template><Head title="My posts"/><div class="mx-auto max-w-5xl p-6"><div class="mb-6 flex justify-between"><h1 class="text-2xl font-semibold">My posts</h1><Link href="/trade-posts/create" class="rounded bg-black px-4 py-2 text-white">Create post</Link></div><div class="space-y-3"><div v-for="post in posts.data" :key="post.id" class="rounded border p-4"><div class="flex justify-between"><strong>{{ post.title }}</strong><span>{{ post.status }}</span></div><div class="text-sm text-gray-600">{{ post.type }} · {{ post.category?.name }}</div></div></div></div></template>
