<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ShoppingCart, Store } from '@lucide/vue';

const props = defineProps<{
    categories: Array<{ id: number; name: string }>;
    allowedTypes: Array<'buy' | 'sell'>;
    defaultType: 'buy' | 'sell';
}>();

const form = useForm({
    type: props.defaultType,
    category_id: '',
    title: '',
    product_name: '',
    description: '',
    hs_code: '',
    quantity: '',
    unit: '',
    price: '',
    currency: 'USD',
    price_terms: '',
    incoterm: '',
    origin_country_code: '',
    destination_country_code: '',
    minimum_order_quantity: '',
    available_from: '',
    expires_at: '',
    custom_fields: {},
    images: [] as File[],
});

const setImages = (event: Event) => {
    form.images = Array.from((event.target as HTMLInputElement).files ?? []);
};

const submit = () => {
    form.post('/trade-posts', {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Create trade post" />

    <div class="mx-auto max-w-5xl space-y-6 p-6">
        <section class="rounded-2xl border bg-card p-6 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight">Create a trade post</h1>
                    <p class="mt-2 text-muted-foreground">
                        Choose what you need, then enter the product and commercial details.
                    </p>
                </div>
                <Link href="/my-posts" class="text-sm font-medium text-primary underline underline-offset-4">
                    My posts
                </Link>
            </div>
        </section>

        <form class="space-y-6" @submit.prevent="submit">
            <section class="rounded-2xl border bg-card p-6">
                <h2 class="text-xl font-semibold">1. What do you want to publish?</h2>
                <p class="mt-1 text-sm text-muted-foreground">Available choices are based on your approved role.</p>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <label v-if="allowedTypes.includes('buy')" class="cursor-pointer">
                        <input v-model="form.type" class="peer sr-only" type="radio" value="buy" />
                        <div class="rounded-xl border-2 p-5 transition peer-checked:border-primary peer-checked:bg-primary/5">
                            <ShoppingCart class="mb-3 size-7" />
                            <div class="font-semibold">Buy request</div>
                            <p class="mt-1 text-sm text-muted-foreground">Tell verified suppliers what product your company wants to purchase.</p>
                        </div>
                    </label>

                    <label v-if="allowedTypes.includes('sell')" class="cursor-pointer">
                        <input v-model="form.type" class="peer sr-only" type="radio" value="sell" />
                        <div class="rounded-xl border-2 p-5 transition peer-checked:border-primary peer-checked:bg-primary/5">
                            <Store class="mb-3 size-7" />
                            <div class="font-semibold">Sell offer</div>
                            <p class="mt-1 text-sm text-muted-foreground">Present a product that your verified company can supply.</p>
                        </div>
                    </label>
                </div>
            </section>

            <section class="rounded-2xl border bg-card p-6">
                <h2 class="text-xl font-semibold">2. Product information</h2>
                <div class="mt-5 grid gap-5 md:grid-cols-2">
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Category *</span>
                        <select v-model="form.category_id" required class="rounded-lg border bg-background p-3">
                            <option value="">Choose category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Product name *</span>
                        <input v-model="form.product_name" required class="rounded-lg border bg-background p-3" placeholder="Example: Frozen trout" />
                    </label>
                    <label class="grid gap-2 md:col-span-2">
                        <span class="text-sm font-medium">Post title *</span>
                        <input v-model="form.title" required class="rounded-lg border bg-background p-3" :placeholder="form.type === 'buy' ? 'Wanted: frozen trout for weekly import' : 'Frozen trout available for export'" />
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">HS code</span>
                        <input v-model="form.hs_code" class="rounded-lg border bg-background p-3" placeholder="Example: 0303" />
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Quantity *</span>
                        <div class="grid grid-cols-[1fr_1fr] gap-2">
                            <input v-model="form.quantity" required type="number" min="0" step="0.001" class="rounded-lg border bg-background p-3" placeholder="1000" />
                            <input v-model="form.unit" required class="rounded-lg border bg-background p-3" placeholder="kg, pcs, pallets" />
                        </div>
                    </label>
                    <label class="grid gap-2 md:col-span-2">
                        <span class="text-sm font-medium">Description and specifications *</span>
                        <textarea v-model="form.description" required class="min-h-40 rounded-lg border bg-background p-3" placeholder="Quality, size, packaging, certificates, frequency, and other requirements" />
                    </label>
                </div>
            </section>

            <section class="rounded-2xl border bg-card p-6">
                <h2 class="text-xl font-semibold">3. Commercial and delivery terms</h2>
                <div class="mt-5 grid gap-5 md:grid-cols-2">
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Price</span>
                        <div class="grid grid-cols-[1fr_100px] gap-2">
                            <input v-model="form.price" type="number" min="0" step="0.01" class="rounded-lg border bg-background p-3" placeholder="0.00" />
                            <input v-model="form.currency" maxlength="3" class="rounded-lg border bg-background p-3 uppercase" placeholder="USD" />
                        </div>
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Incoterm</span>
                        <input v-model="form.incoterm" class="rounded-lg border bg-background p-3" placeholder="EXW, FOB, CIF..." />
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Country of origin</span>
                        <input v-model="form.origin_country_code" maxlength="2" class="rounded-lg border bg-background p-3 uppercase" placeholder="AM" />
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Destination country</span>
                        <input v-model="form.destination_country_code" maxlength="2" class="rounded-lg border bg-background p-3 uppercase" placeholder="US" />
                    </label>
                    <label class="grid gap-2 md:col-span-2">
                        <span class="text-sm font-medium">Additional price or payment terms</span>
                        <input v-model="form.price_terms" class="rounded-lg border bg-background p-3" placeholder="Negotiable, advance payment, price per kg..." />
                    </label>
                    <label class="grid gap-2 md:col-span-2">
                        <span class="text-sm font-medium">Product images</span>
                        <input type="file" multiple accept="image/*" class="rounded-lg border bg-background p-3" @change="setImages" />
                    </label>
                </div>
            </section>

            <div v-if="Object.keys(form.errors).length" class="rounded-xl border border-destructive/40 bg-destructive/10 p-4 text-sm text-destructive">
                <div class="mb-2 font-semibold">Please correct the following:</div>
                <div v-for="(error, key) in form.errors" :key="key">• {{ error }}</div>
            </div>

            <div class="flex flex-col gap-3 rounded-2xl border bg-card p-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-muted-foreground">An administrator will review the post before it becomes public.</p>
                <button :disabled="form.processing" class="rounded-lg bg-primary px-5 py-3 font-medium text-primary-foreground disabled:opacity-50">
                    {{ form.processing ? 'Submitting…' : 'Submit for administrator review' }}
                </button>
            </div>
        </form>
    </div>
</template>
