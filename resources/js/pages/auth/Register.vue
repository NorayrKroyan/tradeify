<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ArrowLeftRight, ShoppingCart, Store } from '@lucide/vue';

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
        description: 'Choose how you trade, then create your account.',
    },
});
</script>

<template>
    <Head title="Create account" />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="rounded-lg border bg-muted/30 p-4">
            <div class="mb-2 flex items-center justify-between text-sm">
                <span class="font-medium">Step 1 of 4</span>
                <span class="text-muted-foreground">Create your account</span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-muted">
                <div class="h-full w-1/4 rounded-full bg-primary" />
            </div>
        </div>

        <div class="space-y-3">
            <div>
                <h2 class="text-base font-semibold">How will you use TradeVerify?</h2>
                <p class="text-sm text-muted-foreground">
                    Choose one option. You can only trade after your company is approved.
                </p>
            </div>

            <div class="grid gap-3 md:grid-cols-3">
                <label class="cursor-pointer">
                    <input
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
            </div>

            <InputError :message="errors.requested_role" />
            <p class="text-xs text-muted-foreground">
                Administrator access is not available during public registration. It is assigned only by the platform owner.
            </p>
        </div>

        <div class="grid gap-5">
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
                :disabled="processing"
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
