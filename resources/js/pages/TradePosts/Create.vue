<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Handshake, ShoppingCart, Store } from '@lucide/vue';
import { watch } from 'vue';
import BrandHeader from '@/components/BrandHeader.vue';

type TradePostType = 'buy' | 'sell' | 'broker';

const props = defineProps<{
    categories: Array<{ id: number; name: string }>;
    allowedTypes: TradePostType[];
    defaultType: TradePostType;
}>();

const form = useForm({
    type: props.defaultType,
    broker_direction:
        props.defaultType === 'broker' ? 'seeking_buyer' : '',
    category_id: '',
    title: '',
    product_name: '',
    description: '',
    hs_code: '',
    quantity: '',
    unit: '',
    price: '',
    target_price: '',
    currency: 'USD',
    broker_commission_type:
        props.defaultType === 'broker' ? 'negotiable' : '',
    broker_commission_value: '',
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

watch(
    () => form.type,
    (type) => {
        if (type === 'broker') {
            if (! form.broker_direction) {
                form.broker_direction = 'seeking_buyer';
            }

            if (! form.broker_commission_type) {
                form.broker_commission_type = 'negotiable';
            }

            return;
        }

        form.broker_direction = '';
        form.broker_commission_type = '';
        form.broker_commission_value = '';
    },
);

watch(
    () => form.broker_commission_type,
    (type) => {
        if (type === 'negotiable' || type === '') {
            form.broker_commission_value = '';
        }
    },
);

const setImages = (event: Event) => {
    form.images = Array.from(
        (event.target as HTMLInputElement).files ?? [],
    );
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

    <div class="mx-auto max-w-6xl space-y-6 p-4 sm:p-6 lg:p-8">
        <BrandHeader
            eyebrow="Publish an opportunity"
            title="Create a trade post"
            description="Choose your marketplace role, describe the product clearly, and add realistic commercial terms so verified companies can evaluate the opportunity quickly."
        >
            <template #actions>
                <Link
                    href="/trade-posts"
                    class="rounded-xl border bg-background px-4 py-2.5 text-sm font-semibold transition hover:bg-muted"
                >
                    Browse marketplace
                </Link>
                <Link
                    href="/my-posts"
                    class="rounded-xl border bg-background px-4 py-2.5 text-sm font-semibold transition hover:bg-muted"
                >
                    My posts
                </Link>
            </template>
        </BrandHeader>

        <section class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-2xl border bg-card p-4 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Step 1</div>
                <div class="mt-1 font-semibold">Choose post type</div>
                <p class="mt-1 text-xs leading-5 text-muted-foreground">Buy, sell or broker based on your approved role.</p>
            </div>
            <div class="rounded-2xl border bg-card p-4 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Step 2</div>
                <div class="mt-1 font-semibold">Describe the product</div>
                <p class="mt-1 text-xs leading-5 text-muted-foreground">Use a clear title, quantity and specification.</p>
            </div>
            <div class="rounded-2xl border bg-card p-4 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Step 3</div>
                <div class="mt-1 font-semibold">Add commercial terms</div>
                <p class="mt-1 text-xs leading-5 text-muted-foreground">Price, target, Incoterm, geography and timing.</p>
            </div>
        </section>

        <form class="space-y-6" @submit.prevent="submit">
            <section class="rounded-2xl border bg-card p-6">
                <h2 class="text-xl font-semibold">
                    1. What do you want to publish?
                </h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Available choices are based on your approved role.
                </p>

                <div class="mt-5 grid gap-4 md:grid-cols-3">
                    <label
                        v-if="allowedTypes.includes('buy')"
                        class="cursor-pointer"
                    >
                        <input
                            v-model="form.type"
                            class="peer sr-only"
                            type="radio"
                            value="buy"
                        />
                        <div class="h-full rounded-xl border-2 p-5 transition peer-checked:border-primary peer-checked:bg-primary/5">
                            <ShoppingCart class="mb-3 size-7" />
                            <div class="font-semibold">Buy request</div>
                            <p class="mt-1 text-sm text-muted-foreground">
                                Tell verified suppliers what product your company wants to purchase.
                            </p>
                        </div>
                    </label>

                    <label
                        v-if="allowedTypes.includes('sell')"
                        class="cursor-pointer"
                    >
                        <input
                            v-model="form.type"
                            class="peer sr-only"
                            type="radio"
                            value="sell"
                        />
                        <div class="h-full rounded-xl border-2 p-5 transition peer-checked:border-primary peer-checked:bg-primary/5">
                            <Store class="mb-3 size-7" />
                            <div class="font-semibold">Sell offer</div>
                            <p class="mt-1 text-sm text-muted-foreground">
                                Present a product that your verified company can supply.
                            </p>
                        </div>
                    </label>

                    <label
                        v-if="allowedTypes.includes('broker')"
                        class="cursor-pointer"
                    >
                        <input
                            v-model="form.type"
                            class="peer sr-only"
                            type="radio"
                            value="broker"
                        />
                        <div class="h-full rounded-xl border-2 p-5 transition peer-checked:border-primary peer-checked:bg-primary/5">
                            <Handshake class="mb-3 size-7" />
                            <div class="font-semibold">Broker opportunity</div>
                            <p class="mt-1 text-sm text-muted-foreground">
                                Connect verified buyers and sellers without presenting yourself as the goods owner.
                            </p>
                        </div>
                    </label>
                </div>

                <div
                    v-if="form.type === 'broker'"
                    class="mt-5 rounded-xl border bg-muted/30 p-4"
                >
                    <div class="font-medium">Broker direction *</div>
                    <p class="mt-1 text-sm text-muted-foreground">
                        State which side of the transaction you are looking for.
                    </p>

                    <div class="mt-3 flex flex-col gap-3 sm:flex-row">
                        <label class="flex cursor-pointer items-center gap-2">
                            <input
                                v-model="form.broker_direction"
                                type="radio"
                                value="seeking_buyer"
                                required
                            />
                            Seeking a buyer
                        </label>

                        <label class="flex cursor-pointer items-center gap-2">
                            <input
                                v-model="form.broker_direction"
                                type="radio"
                                value="seeking_seller"
                                required
                            />
                            Seeking a seller
                        </label>
                    </div>

                    <p class="mt-3 text-xs text-muted-foreground">
                        Broker listings identify the posting company as an intermediary and do not imply ownership of the goods.
                    </p>
                </div>
            </section>

            <section class="rounded-2xl border bg-card p-6">
                <h2 class="text-xl font-semibold">2. Product information</h2>

                <div class="mt-5 grid gap-5 md:grid-cols-2">
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Category *</span>
                        <select
                            v-model="form.category_id"
                            required
                            class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                        >
                            <option value="">Choose category</option>
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                    </label>

                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Product name *</span>
                        <input
                            v-model="form.product_name"
                            required
                            class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                            placeholder="Example: Frozen trout"
                        />
                    </label>

                    <label class="grid gap-2 md:col-span-2">
                        <span class="text-sm font-medium">Post title *</span>
                        <input
                            v-model="form.title"
                            required
                            class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                            :placeholder="
                                form.type === 'buy'
                                    ? 'Wanted: frozen trout for weekly import'
                                    : form.type === 'sell'
                                      ? 'Frozen trout available for export'
                                      : 'Broker opportunity: frozen trout — seeking buyer'
                            "
                        />
                    </label>

                    <label class="grid gap-2">
                        <span class="text-sm font-medium">HS code</span>
                        <input
                            v-model="form.hs_code"
                            class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                            placeholder="Example: 0303"
                        />
                    </label>

                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Quantity *</span>
                        <div class="grid grid-cols-[1fr_1fr] gap-2">
                            <input
                                v-model="form.quantity"
                                required
                                type="number"
                                min="0"
                                step="0.001"
                                class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                                placeholder="1000"
                            />
                            <input
                                v-model="form.unit"
                                required
                                class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                                placeholder="kg, pcs, pallets"
                            />
                        </div>
                    </label>

                    <label class="grid gap-2 md:col-span-2">
                        <span class="text-sm font-medium">
                            Description and specifications *
                        </span>
                        <textarea
                            v-model="form.description"
                            required
                            class="min-h-40 rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                            placeholder="Quality, size, packaging, certificates, frequency, mandate details, and other requirements"
                        />
                    </label>
                </div>
            </section>

            <section class="rounded-2xl border bg-card p-6">
                <h2 class="text-xl font-semibold">
                    3. Commercial and delivery terms
                </h2>

                <div class="mt-5 grid gap-5 md:grid-cols-2">
                    <label class="grid gap-2">
                        <span class="text-sm font-medium">
                            {{
                                form.type === 'sell'
                                    ? 'Asking price'
                                    : form.type === 'buy'
                                      ? 'Indicative price'
                                      : 'Reference price'
                            }}
                        </span>
                        <div class="grid grid-cols-[1fr_100px] gap-2">
                            <input
                                v-model="form.price"
                                type="number"
                                min="0"
                                step="0.01"
                                class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                                placeholder="0.00"
                            />
                            <input
                                v-model="form.currency"
                                maxlength="3"
                                class="rounded-lg border bg-background p-3 uppercase"
                                placeholder="USD"
                            />
                        </div>
                    </label>

                    <label class="grid gap-2">
                        <span class="text-sm font-medium">
                            {{
                                form.type === 'broker'
                                    ? 'Target transaction price'
                                    : 'Target price'
                            }}
                        </span>
                        <input
                            v-model="form.target_price"
                            type="number"
                            min="0"
                            step="0.01"
                            class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                            :placeholder="
                                form.type === 'buy'
                                    ? 'Preferred / maximum buying price'
                                    : form.type === 'sell'
                                      ? 'Preferred selling price'
                                      : 'Target transaction price'
                            "
                        />
                    </label>

                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Incoterm</span>
                        <input
                            v-model="form.incoterm"
                            class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                            placeholder="EXW, FOB, CIF..."
                        />
                    </label>

                    <label class="grid gap-2">
                        <span class="text-sm font-medium">
                            Minimum order quantity
                        </span>
                        <input
                            v-model="form.minimum_order_quantity"
                            type="number"
                            min="0"
                            step="0.001"
                            class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                            placeholder="Optional"
                        />
                    </label>

                    <label class="grid gap-2">
                        <span class="text-sm font-medium">
                            Country of origin
                        </span>
                        <input
                            v-model="form.origin_country_code"
                            maxlength="2"
                            class="rounded-lg border bg-background p-3 uppercase"
                            placeholder="AM"
                        />
                    </label>

                    <label class="grid gap-2">
                        <span class="text-sm font-medium">
                            Destination country
                        </span>
                        <input
                            v-model="form.destination_country_code"
                            maxlength="2"
                            class="rounded-lg border bg-background p-3 uppercase"
                            placeholder="US"
                        />
                    </label>

                    <template v-if="form.type === 'broker'">
                        <label class="grid gap-2">
                            <span class="text-sm font-medium">
                                Broker commission *
                            </span>
                            <select
                                v-model="form.broker_commission_type"
                                required
                                class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                            >
                                <option value="negotiable">Negotiable</option>
                                <option value="percentage">Percentage</option>
                                <option value="fixed">Fixed amount</option>
                            </select>
                        </label>

                        <label
                            v-if="
                                form.broker_commission_type === 'percentage'
                                || form.broker_commission_type === 'fixed'
                            "
                            class="grid gap-2"
                        >
                            <span class="text-sm font-medium">
                                {{
                                    form.broker_commission_type === 'percentage'
                                        ? 'Commission % *'
                                        : `Commission amount (${form.currency || 'currency'}) *`
                                }}
                            </span>
                            <input
                                v-model="form.broker_commission_value"
                                required
                                type="number"
                                min="0"
                                :max="
                                    form.broker_commission_type === 'percentage'
                                        ? 100
                                        : undefined
                                "
                                step="0.01"
                                class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                                placeholder="0.00"
                            />
                        </label>
                    </template>

                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Available from</span>
                        <input
                            v-model="form.available_from"
                            type="date"
                            class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                        />
                    </label>

                    <label class="grid gap-2">
                        <span class="text-sm font-medium">Post expires</span>
                        <input
                            v-model="form.expires_at"
                            type="date"
                            class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                        />
                    </label>

                    <label class="grid gap-2 md:col-span-2">
                        <span class="text-sm font-medium">
                            Additional price or payment terms
                        </span>
                        <input
                            v-model="form.price_terms"
                            class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                            placeholder="Negotiable, advance payment, price per kg..."
                        />
                    </label>

                    <label class="grid gap-2 md:col-span-2">
                        <span class="text-sm font-medium">Product images</span>
                        <input
                            type="file"
                            multiple
                            accept="image/*"
                            class="rounded-xl border bg-background p-3 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/15"
                            @change="setImages"
                        />
                    </label>
                </div>
            </section>

            <div
                v-if="Object.keys(form.errors).length"
                class="rounded-xl border border-destructive/40 bg-destructive/10 p-4 text-sm text-destructive"
            >
                <div class="mb-2 font-semibold">
                    Please correct the following:
                </div>
                <div
                    v-for="(error, key) in form.errors"
                    :key="key"
                >
                    • {{ error }}
                </div>
            </div>

            <div class="flex flex-col gap-3 rounded-2xl border bg-card p-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-muted-foreground">
                    An administrator will review the post before it becomes public.
                </p>
                <button
                    :disabled="form.processing"
                    class="rounded-lg bg-primary px-5 py-3 font-medium text-primary-foreground disabled:opacity-50"
                >
                    {{
                        form.processing
                            ? 'Submitting…'
                            : 'Submit for administrator review'
                    }}
                </button>
            </div>
        </form>
    </div>
</template>
