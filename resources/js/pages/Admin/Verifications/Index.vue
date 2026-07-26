<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
defineProps<{ companies: any; filters: any }>();
</script>

<template>
    <Head title="Verification queue" />
    <div class="mx-auto max-w-7xl p-6">
        <h1 class="mb-6 text-2xl font-semibold">Company verification queue</h1>
        <div class="overflow-x-auto rounded-lg border">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50"><tr><th class="p-3">Company</th><th class="p-3">Country</th><th class="p-3">Activity</th><th class="p-3">Status</th><th class="p-3">Submitted</th><th class="p-3"></th></tr></thead>
                <tbody>
                    <tr v-for="company in companies.data" :key="company.id" class="border-t">
                        <td class="p-3"><div class="font-medium">{{ company.legal_name }}</div><div class="text-gray-500">{{ company.owner.email }}</div></td>
                        <td class="p-3">{{ company.country_code }}</td>
                        <td class="p-3">{{ company.activity_type }}</td>
                        <td class="p-3">{{ company.verification_status }}</td>
                        <td class="p-3">{{ company.submitted_at }}</td>
                        <td class="p-3"><Link :href="`/admin/verifications/${company.id}`" class="underline">Review</Link></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
