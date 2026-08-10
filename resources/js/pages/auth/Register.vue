<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import {
    ArrowLeftRight,
    FileCheck2,
    Handshake,
    Plus,
    ShoppingCart,
    Store,
    Trash2,
    Upload,
} from '@lucide/vue';
import { computed, ref, watch } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: 'Join TradeVerify',
        description: 'Choose how you trade, upload your license, then create your account.',
    },
});

type MarketplaceRole = 'buyer' | 'seller' | 'both' | 'broker' | '';

type LicenseRow = {
    id: number;
    type: string;
};

const selectedRole = ref<MarketplaceRole>('');
let nextLicenseId = 2;
const licenses = ref<LicenseRow[]>([
    { id: 1, type: '' },
]);

const licenseOptions = computed(() => {
    switch (selectedRole.value) {
        case 'buyer':
            return [
                { value: 'business_license', label: 'Business / trade license' },
                { value: 'import_license', label: 'Import license / authorization' },
                { value: 'trade_license', label: 'Commercial trading license' },
                { value: 'other_license', label: 'Other business authorization' },
            ];
        case 'seller':
            return [
                { value: 'business_license', label: 'Business / trade license' },
                { value: 'export_license', label: 'Export license / authorization' },
                { value: 'manufacturing_license', label: 'Manufacturing / producer license' },
                { value: 'quality_certificate', label: 'Quality / compliance certificate' },
                { value: 'other_license', label: 'Other business authorization' },
            ];
        case 'both':
            return [
                { value: 'business_license', label: 'Business / trade license' },
                { value: 'import_export_license', label: 'Import / export license' },
                { value: 'manufacturing_license', label: 'Manufacturing / producer license' },
                { value: 'quality_certificate', label: 'Quality / compliance certificate' },
                { value: 'other_license', label: 'Other business authorization' },
            ];
        case 'broker':
            return [
                { value: 'broker_license', label: 'Broker / intermediary license' },
                { value: 'intermediary_mandate', label: 'Broker mandate / authorization' },
                { value: 'business_license', label: 'Business / trade license' },
                { value: 'other_license', label: 'Other intermediary authorization' },
            ];
        default:
            return [];
    }
});

const roleLicenseHint = computed(() => {
    switch (selectedRole.value) {
        case 'buyer':
            return 'Upload at least one document proving that you are authorized to operate as a buyer/importer or trading business.';
        case 'seller':
            return 'Upload at least one document proving that you are authorized to sell/export/manufacture the products you offer.';
        case 'both':
            return 'Upload at least one business or import/export authorization document.';
        case 'broker':
            return 'Upload at least one broker/intermediary license, mandate, or other document proving your authority to act as an intermediary.';
        default:
            return 'Choose your marketplace role first. The appropriate license types will then appear.';
    }
});

watch(selectedRole, () => {
    licenses.value = [{ id: nextLicenseId++, type: '' }];
});

const addLicense = () => {
    if (licenses.value.length >= 5) {
        return;
    }

    licenses.value.push({ id: nextLicenseId++, type: '' });
};

const removeLicense = (index: number) => {
    if (licenses.value.length === 1) {
        return;
    }

    licenses.value.splice(index, 1);
};
</script>

<template>
    <Head title="Create account" />

    <Form
        v-bind="store.form()"
        enctype="multipart/form-data"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="rounded-lg border bg-muted/30 p-4">
            <div class="mb-2 flex items-center justify-between text-sm">
                <span class="font-medium">Create marketplace account</span>
                <span class="text-muted-foreground">Role + license + account</span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-muted">
                <div class="h-full w-full rounded-full bg-primary" />
            </div>
        </div>

        <div class="space-y-3">
            <div>
                <h2 class="text-base font-semibold">1. How will you use TradeVerify?</h2>
                <p class="text-sm text-muted-foreground">
                    Choose your marketplace role. Your license documents will be reviewed before trading access is approved.
                </p>
            </div>

            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                <label class="cursor-pointer">
                    <input
                        v-model="selectedRole"
                        class="peer sr-only"
                        type="radio"
                        name="requested_role"
                        value="buyer"
                        required
                    />
                    <div class="h-full rounded-xl border-2 p-4 transition hover:border-primary/50 peer-checked:border-primary peer-checked:bg-primary/5">
                        <ShoppingCart class="mb-3 size-6" />
                        <div class="font-semibold">Buyer</div>
                        <div class="mt-1 text-sm text-muted-foreground">
                            I want to find products and publish buying requests.
                        </div>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input
                        v-model="selectedRole"
                        class="peer sr-only"
                        type="radio"
                        name="requested_role"
                        value="seller"
                        required
                    />
                    <div class="h-full rounded-xl border-2 p-4 transition hover:border-primary/50 peer-checked:border-primary peer-checked:bg-primary/5">
                        <Store class="mb-3 size-6" />
                        <div class="font-semibold">Seller</div>
                        <div class="mt-1 text-sm text-muted-foreground">
                            I want to offer products and publish selling offers.
                        </div>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input
                        v-model="selectedRole"
                        class="peer sr-only"
                        type="radio"
                        name="requested_role"
                        value="both"
                        required
                    />
                    <div class="h-full rounded-xl border-2 p-4 transition hover:border-primary/50 peer-checked:border-primary peer-checked:bg-primary/5">
                        <ArrowLeftRight class="mb-3 size-6" />
                        <div class="font-semibold">Buyer and seller</div>
                        <div class="mt-1 text-sm text-muted-foreground">
                            I want to buy products and also sell products.
                        </div>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input
                        v-model="selectedRole"
                        class="peer sr-only"
                        type="radio"
                        name="requested_role"
                        value="broker"
                        required
                    />
                    <div class="h-full rounded-xl border-2 p-4 transition hover:border-primary/50 peer-checked:border-primary peer-checked:bg-primary/5">
                        <Handshake class="mb-3 size-6" />
                        <div class="font-semibold">Broker</div>
                        <div class="mt-1 text-sm text-muted-foreground">
                            I connect verified buyers and sellers and publish broker opportunities.
                        </div>
                    </div>
                </label>
            </div>

            <InputError :message="errors.requested_role" />

            <p class="text-xs text-muted-foreground">
                Administrator access is not available during public registration. It is assigned only by the platform owner.
            </p>
        </div>

        <div class="rounded-xl border p-5">
            <div class="mb-4 flex items-start gap-3">
                <div class="rounded-lg bg-primary/10 p-2">
                    <FileCheck2 class="size-5 text-primary" />
                </div>
                <div>
                    <h2 class="font-semibold">2. License / authorization documents</h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ roleLicenseHint }}
                    </p>
                    <p class="mt-1 text-xs text-muted-foreground">
                        PDF, JPG, JPEG or PNG. Maximum 10 MB per file. You may upload up to 5 documents.
                    </p>
                </div>
            </div>

            <InputError :message="errors.licenses" />

            <div v-if="!selectedRole" class="rounded-lg border border-dashed p-5 text-center text-sm text-muted-foreground">
                Select Buyer, Seller, Buyer and seller, or Broker above to continue.
            </div>

            <div v-else class="space-y-4">
                <div
                    v-for="(license, index) in licenses"
                    :key="license.id"
                    class="rounded-lg border bg-muted/20 p-4"
                >
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <div class="font-medium">License {{ index + 1 }}</div>
                        <Button
                            v-if="licenses.length > 1"
                            type="button"
                            variant="ghost"
                            size="sm"
                            @click="removeLicense(index)"
                        >
                            <Trash2 class="mr-1 size-4" />
                            Remove
                        </Button>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label :for="`license_type_${index}`">License type</Label>
                            <select
                                :id="`license_type_${index}`"
                                v-model="license.type"
                                :name="`licenses[${index}][type]`"
                                required
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                            >
                                <option value="" disabled>Select license type</option>
                                <option
                                    v-for="option in licenseOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>
                            <InputError :message="errors[`licenses.${index}.type`]" />
                        </div>

                        <div class="grid gap-2">
                            <Label :for="`license_file_${index}`">License file</Label>
                            <div class="relative">
                                <Upload class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                                <Input
                                    :id="`license_file_${index}`"
                                    type="file"
                                    :name="`licenses[${index}][file]`"
                                    accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"
                                    required
                                    class="pl-9"
                                />
                            </div>
                            <InputError :message="errors[`licenses.${index}.file`]" />
                        </div>
                    </div>
                </div>

                <Button
                    type="button"
                    variant="outline"
                    class="w-full"
                    :disabled="licenses.length >= 5"
                    @click="addLicense"
                >
                    <Plus class="mr-2 size-4" />
                    Add another license
                </Button>
            </div>
        </div>

        <div class="grid gap-5">
            <div>
                <h2 class="text-base font-semibold">3. Account details</h2>
                <p class="text-sm text-muted-foreground">
                    Create the account that will own the marketplace application.
                </p>
            </div>

            <div class="grid gap-2">
                <Label for="name">Full name</Label>
                <Input
                    id="name"
                    type="text"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="name"
                    name="name"
                    placeholder="Your full name"
                />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="email">Business email address</Label>
                <Input
                    id="email"
                    type="email"
                    required
                    :tabindex="2"
                    autocomplete="email"
                    name="email"
                    placeholder="name@company.com"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="password">Password</Label>
                <PasswordInput
                    id="password"
                    required
                    :tabindex="3"
                    autocomplete="new-password"
                    name="password"
                    placeholder="Create a secure password"
                    :passwordrules="passwordRules"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirm password</Label>
                <PasswordInput
                    id="password_confirmation"
                    required
                    :tabindex="4"
                    autocomplete="new-password"
                    name="password_confirmation"
                    placeholder="Repeat your password"
                    :passwordrules="passwordRules"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="mt-2 w-full"
                tabindex="5"
                :disabled="processing || !selectedRole"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" />
                Create account and verify email
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            Already have an account?
            <TextLink
                :href="login()"
                class="underline underline-offset-4"
                :tabindex="6"
            >
                Log in
            </TextLink>
        </div>
    </Form>
</template>
