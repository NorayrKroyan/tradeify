<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
const props = defineProps<{ company: any }>();
const form = useForm({ decision: 'approve', notes: '', checks: { registration_confirmed: false, tax_confirmed: false, representative_confirmed: false, address_confirmed: false, sanctions_screened: false } });
const submit = () => form.post(`/admin/verifications/${props.company.id}/decision`);
</script>

<template>
    <Head :title="`Review ${company.legal_name}`" />
    <div class="mx-auto max-w-5xl space-y-6 p-6">
        <div><h1 class="text-2xl font-semibold">{{ company.legal_name }}</h1><p>{{ company.country_code }} · {{ company.registration_number }} · {{ company.tax_number }}</p></div>
        <section class="rounded-lg border p-5"><h2 class="mb-3 font-semibold">Company information</h2><p class="whitespace-pre-line">{{ company.description }}</p><p class="mt-3">{{ company.registered_address }}</p></section>
        <section class="rounded-lg border p-5"><h2 class="mb-3 font-semibold">Documents</h2><div v-for="doc in company.documents" :key="doc.id" class="flex justify-between border-b py-2"><span>{{ doc.document_type }} — {{ doc.original_name }}</span><a :href="`/verification-documents/${doc.id}/download`" class="underline">Download securely</a></div></section>
        <form class="space-y-4 rounded-lg border p-5" @submit.prevent="submit">
            <h2 class="font-semibold">Decision</h2>
            <div class="grid gap-2 md:grid-cols-2">
                <label><input v-model="form.checks.registration_confirmed" type="checkbox" /> Registration confirmed</label>
                <label><input v-model="form.checks.tax_confirmed" type="checkbox" /> Tax registration confirmed</label>
                <label><input v-model="form.checks.representative_confirmed" type="checkbox" /> Representative confirmed</label>
                <label><input v-model="form.checks.address_confirmed" type="checkbox" /> Address confirmed</label>
                <label><input v-model="form.checks.sanctions_screened" type="checkbox" /> Sanctions screening completed</label>
            </div>
            <select v-model="form.decision" class="rounded border p-2"><option value="approve">Approve</option><option value="reject">Reject</option><option value="suspend">Suspend</option></select>
            <textarea v-model="form.notes" class="min-h-28 w-full rounded border p-2" placeholder="Decision notes" />
            <button class="rounded bg-black px-5 py-3 text-white">Save decision</button>
        </form>
    </div>
</template>
