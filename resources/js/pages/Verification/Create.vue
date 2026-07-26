<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Building2, FileCheck2, ShieldCheck } from '@lucide/vue';
import { computed } from 'vue';

type Company = {
    legal_name?: string;
    trading_name?: string | null;
    registration_number?: string;
    tax_number?: string;
    activity_type?: string;
    country_code?: string;
    registered_address?: string;
    operating_address?: string | null;
    website?: string | null;
    business_email?: string;
    business_phone?: string;
    description?: string;
};

type DocumentRow = {
    type: string;
    label: string;
    help: string;
    file: File | null;
    required: boolean;
};

const props = defineProps<{
    company: Company | null;
    suggestedActivityType: 'importer' | 'exporter' | 'both';
    userEmail: string;
}>();

const initialActivityType = props.company?.activity_type ?? props.suggestedActivityType;

const form = useForm({
    legal_name: props.company?.legal_name ?? '',
    trading_name: props.company?.trading_name ?? '',
    registration_number: props.company?.registration_number ?? '',
    tax_number: props.company?.tax_number ?? '',
    activity_type: initialActivityType,
    country_code: props.company?.country_code ?? 'AM',
    registered_address: props.company?.registered_address ?? '',
    operating_address: props.company?.operating_address ?? '',
    website: props.company?.website ?? '',
    business_email: props.company?.business_email ?? props.userEmail,
    business_phone: props.company?.business_phone ?? '',
    description: props.company?.description ?? '',
    additional_information: {},
    documents: [
        {
            type: 'business_registration',
            label: 'Business registration certificate',
            help: 'Official document proving that the company is legally registered.',
            file: null,
            required: true,
        },
        {
            type: 'tax_registration',
            label: 'Tax or VAT registration',
            help: 'Document showing the company tax number or VAT number.',
            file: null,
            required: true,
        },
        {
            type: 'representative_id',
            label: 'Legal representative identification',
            help: 'Passport or ID of the person authorized to represent the company.',
            file: null,
            required: true,
        },
        {
            type: 'address_proof',
            label: 'Proof of company address',
            help: 'Recent official document confirming the registered business address.',
            file: null,
            required: true,
        },
    ] as DocumentRow[],
});

const roleLabel = computed(() => {
    if (form.activity_type === 'importer') return 'Buyer / Importer';
    if (form.activity_type === 'exporter') return 'Seller / Exporter';
    return 'Buyer and seller / Importer and exporter';
});

const addDocument = () => {
    form.documents.push({
        type: 'other',
        label: 'Additional supporting document',
        help: 'Optional license, certificate, bank letter, or other supporting file.',
        file: null,
        required: false,
    });
};

const setDocumentFile = (index: number, event: Event) => {
    form.documents[index].file =
        (event.target as HTMLInputElement).files?.[0] ?? null;
};

const removeDocument = (index: number) => {
    if (!form.documents[index].required) {
        form.documents.splice(index, 1);
    }
};

const submit = () => {
    form.post('/verification/apply', {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Verify your company" />

    <div class="mx-auto max-w-5xl space-y-6 p-6">
        <section class="rounded-2xl border bg-card p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between text-sm">
                <span class="font-medium">Step 3 of 4</span>
                <Link href="/dashboard" class="text-primary underline underline-offset-4">
                    Back to setup dashboard
                </Link>
            </div>
            <div class="mb-6 h-2 overflow-hidden rounded-full bg-muted">
                <div class="h-full w-3/4 rounded-full bg-primary" />
            </div>

            <div class="flex items-start gap-4">
                <ShieldCheck class="mt-1 size-8 shrink-0" />
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight">Verify your company</h1>
                    <p class="mt-2 max-w-3xl text-muted-foreground">
                        TradeVerify reviews every company before it can publish. Complete the legal details and upload the four required documents.
                    </p>
                </div>
            </div>
        </section>

        <form class="space-y-6" @submit.prevent="submit">
            <section class="rounded-2xl border bg-card p-6">
                <div class="mb-5 flex items-start gap-3">
                    <Building2 class="mt-1 size-6 shrink-0" />
                    <div>
                        <h2 class="text-xl font-semibold">1. Company details</h2>
                        <p class="text-sm text-muted-foreground">Enter the information exactly as it appears in official documents.</p>
                    </div>
                </div>

                <div class="mb-5 rounded-xl border bg-muted/30 p-4">
                    <div class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Your marketplace role</div>
                    <div class="mt-1 font-semibold">{{ roleLabel }}</div>
                    <p class="mt-1 text-sm text-muted-foreground">This was selected when the account was created.</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Legal company name *</span>
                        <input v-model="form.legal_name" required class="rounded-lg border bg-background p-3" placeholder="Name from registration certificate" />
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Trading name</span>
                        <input v-model="form.trading_name" class="rounded-lg border bg-background p-3" placeholder="Brand or commercial name" />
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Registration number *</span>
                        <input v-model="form.registration_number" required class="rounded-lg border bg-background p-3" placeholder="Official registration number" />
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Tax / VAT number *</span>
                        <input v-model="form.tax_number" required class="rounded-lg border bg-background p-3" placeholder="Tax identification number" />
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Country code *</span>
                        <input v-model="form.country_code" required maxlength="2" class="rounded-lg border bg-background p-3 uppercase" placeholder="AM" />
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Business phone *</span>
                        <input v-model="form.business_phone" required class="rounded-lg border bg-background p-3" placeholder="+374..." />
                    </label>
                    <label class="grid gap-2 md:col-span-2">
                        <span class="text-sm font-medium">Business email *</span>
                        <input v-model="form.business_email" required type="email" class="rounded-lg border bg-background p-3" placeholder="company@example.com" />
                    </label>
                    <label class="grid gap-2 md:col-span-2">
                        <span class="text-sm font-medium">Website</span>
                        <input v-model="form.website" type="url" class="rounded-lg border bg-background p-3" placeholder="https://example.com" />
                    </label>
                    <label class="grid gap-2 md:col-span-2">
                        <span class="text-sm font-medium">Registered address *</span>
                        <textarea v-model="form.registered_address" required class="min-h-24 rounded-lg border bg-background p-3" placeholder="Full legal address" />
                    </label>
                    <label class="grid gap-2 md:col-span-2">
                        <span class="text-sm font-medium">Operating address</span>
                        <textarea v-model="form.operating_address" class="min-h-24 rounded-lg border bg-background p-3" placeholder="Warehouse, factory, or office address if different" />
                    </label>
                    <label class="grid gap-2 md:col-span-2">
                        <span class="text-sm font-medium">Company activity and products *</span>
                        <textarea v-model="form.description" required class="min-h-36 rounded-lg border bg-background p-3" placeholder="Describe what the company imports, exports, manufactures, or distributes" />
                    </label>
                </div>
            </section>

            <section class="rounded-2xl border bg-card p-6">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <FileCheck2 class="mt-1 size-6 shrink-0" />
                        <div>
                            <h2 class="text-xl font-semibold">2. Verification documents</h2>
                            <p class="text-sm text-muted-foreground">PDF, JPG, JPEG, or PNG. Maximum 10 MB per file.</p>
                        </div>
                    </div>
                    <button type="button" class="rounded-lg border px-3 py-2 text-sm font-medium" @click="addDocument">
                        Add optional document
                    </button>
                </div>

                <div class="space-y-4">
                    <div v-for="(document, index) in form.documents" :key="`${document.type}-${index}`" class="rounded-xl border p-4">
                        <div class="grid gap-4 md:grid-cols-[1fr_1.2fr_auto] md:items-center">
                            <div>
                                <div class="font-medium">{{ document.label }} <span v-if="document.required">*</span></div>
                                <div class="mt-1 text-sm text-muted-foreground">{{ document.help }}</div>
                            </div>

                            <div class="grid gap-2">
                                <select v-if="!document.required" v-model="document.type" class="rounded-lg border bg-background p-2 text-sm">
                                    <option value="import_export_license">Import/export license</option>
                                    <option value="bank_letter">Bank confirmation letter</option>
                                    <option value="beneficial_ownership">Beneficial ownership declaration</option>
                                    <option value="quality_certificate">Quality certificate</option>
                                    <option value="other">Other supporting document</option>
                                </select>
                                <input
                                    type="file"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    :required="document.required"
                                    class="rounded-lg border bg-background p-2 text-sm"
                                    @change="setDocumentFile(index, $event)"
                                />
                            </div>

                            <button v-if="!document.required" type="button" class="text-sm text-destructive" @click="removeDocument(index)">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <div v-if="Object.keys(form.errors).length" class="rounded-xl border border-destructive/40 bg-destructive/10 p-4 text-sm text-destructive">
                <div class="mb-2 font-semibold">Please correct the following:</div>
                <div v-for="(error, key) in form.errors" :key="key">• {{ error }}</div>
            </div>

            <div class="flex flex-col gap-3 rounded-2xl border bg-card p-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-muted-foreground">
                    After submission, an administrator will review your company and documents.
                </p>
                <button :disabled="form.processing" class="rounded-lg bg-primary px-5 py-3 font-medium text-primary-foreground disabled:opacity-50">
                    {{ form.processing ? 'Submitting…' : 'Submit for administrator review' }}
                </button>
            </div>
        </form>
    </div>
</template>
