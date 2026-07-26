<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
defineProps<{ users: any }>();
const update = (user: any) => {
    const roles = user.roles.map((role: any) => role.name).filter((name: string) => name !== 'super-admin');
    router.put(`/admin/users/${user.id}`, { is_active: !user.is_active, roles });
};
</script>
<template>
    <Head title="User administration" />
    <div class="mx-auto max-w-7xl p-6">
        <h1 class="mb-6 text-2xl font-semibold">Users and permissions</h1>
        <div class="overflow-x-auto rounded-lg border">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50"><tr><th class="p-3">User</th><th class="p-3">Company</th><th class="p-3">Roles</th><th class="p-3">Active</th><th class="p-3"></th></tr></thead>
                <tbody><tr v-for="user in users.data" :key="user.id" class="border-t"><td class="p-3"><strong>{{ user.name }}</strong><div>{{ user.email }}</div></td><td class="p-3">{{ user.primary_company?.legal_name || 'No company' }}</td><td class="p-3">{{ user.roles.map((r:any) => r.name).join(', ') }}</td><td class="p-3">{{ user.is_active ? 'Yes' : 'No' }}</td><td class="p-3"><button class="rounded border px-3 py-2" @click="update(user)">{{ user.is_active ? 'Deactivate' : 'Activate' }}</button></td></tr></tbody>
            </table>
        </div>
    </div>
</template>
