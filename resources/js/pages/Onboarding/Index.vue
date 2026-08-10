<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import {
    BadgeCheck,
    Building2,
    Check,
    Circle,
    Clock3,
    FileCheck2,
    Handshake,
    MailCheck,
    ShieldCheck,
    ShoppingCart,
    Store,
} from '@lucide/vue';
import { computed } from 'vue';

const props = defineProps<{
    profile: {
        name: string;
        email: string;
        requested_role: 'buyer' | 'seller' | 'both' | 'broker' | null;
        email_verified: boolean;
        roles: string[];
        is_admin: boolean;
    };
    company: null | {
        id: number;
        legal_name: string;
        activity_type: string;
        verification_status: string;
        rejection_reason: string | null;
        suspension_reason: string | null;
        documents_count: number;
        is_approved: boolean;
    };
}>();

const roleLabel = computed(() => {
    if (props.profile.requested_role === 'buyer') return 'Buyer';
    if (props.profile.requested_role === 'seller') return 'Seller';
    if (props.profile.requested_role === 'both') return 'Buyer and seller';
    if (props.profile.requested_role === 'broker') return 'Broker';

    return 'Platform administrator';
});

const companySubmitted = computed(() => Boolean(props.company));

const companyPending = computed(() =>
    ['pending', 'under_review'].includes(
        props.company?.verification_status ?? '',
    ),
);

const companyRejected = computed(
    () => props.company?.verification_status === 'rejected',
);

const companyApproved = computed(
    () => props.company?.is_approved === true,
);

const steps = computed(() => [
    {
        title: 'Account created',
        description: `${roleLabel.value} account for ${props.profile.email}`,
        done: true,
        active: false,
    },
    {
        title: 'Verify your email',
        description: props.profile.email_verified
            ? 'Your email address is confirmed.'
            : 'Open the verification email and click the verification button.',
        done: props.profile.email_verified,
        active: ! props.profile.email_verified,
    },
    {
        title: 'Submit company information',
        description: companySubmitted.value
            ? `${props.company?.legal_name} and ${props.company?.documents_count ?? 0} document(s) submitted.`
            : 'Provide legal company details and four required documents.',
        done: companySubmitted.value,
        active: props.profile.email_verified && ! companySubmitted.value,
    },
    {
        title: 'Administrator review',
        description: companyApproved.value
            ? 'Your company has been approved.'
            : companyPending.value
              ? 'The TradeVerify administrator is reviewing your documents.'
              : companyRejected.value
                ? 'Corrections are required before approval.'
                : 'Review starts after your company application is submitted.',
        done: companyApproved.value,
        active: companyPending.value || companyRejected.value,
    },
]);
</script>

<template>
    <Head title="Getting started" />

    <div class="mx-auto max-w-5xl space-y-6 p-6">
        <section class="rounded-2xl border bg-card p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div>
                    <p class="text-sm font-medium text-primary">TradeVerify setup</p>
                    <h1 class="mt-1 text-3xl font-semibold tracking-tight">
                        Welcome, {{ profile.name }}
                    </h1>
                    <p class="mt-2 max-w-2xl text-muted-foreground">
                        Complete the steps below. Only approved companies can publish buy requests, sell offers, or broker opportunities.
                    </p>
                </div>

                <div class="rounded-xl border px-4 py-3 text-sm">
                    <div class="text-muted-foreground">Selected account type</div>
                    <div class="mt-1 font-semibold">{{ roleLabel }}</div>
                </div>
            </div>
        </section>

        <section
            v-if="profile.is_admin"
            class="rounded-2xl border border-primary/30 bg-primary/5 p-6"
        >
            <div class="flex items-start gap-4">
                <ShieldCheck class="mt-1 size-7 shrink-0" />
                <div class="flex-1">
                    <h2 class="text-xl font-semibold">Administrator workspace</h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Review companies, manage users, and approve marketplace posts.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <Link
                            href="/admin/verifications"
                            class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground"
                        >
                            Review companies
                        </Link>
                        <Link
                            href="/admin/users"
                            class="rounded-lg border bg-background px-4 py-2 text-sm font-medium"
                        >
                            Manage users
                        </Link>
                        <Link
                            href="/admin/trade-posts"
                            class="rounded-lg border bg-background px-4 py-2 text-sm font-medium"
                        >
                            Moderate posts
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <section v-else class="grid gap-4">
            <div
                v-for="(step, index) in steps"
                :key="step.title"
                class="flex gap-4 rounded-2xl border bg-card p-5"
                :class="step.active ? 'border-primary ring-1 ring-primary/20' : ''"
            >
                <div
                    class="flex size-10 shrink-0 items-center justify-center rounded-full border"
                    :class="step.done ? 'border-primary bg-primary text-primary-foreground' : step.active ? 'border-primary text-primary' : 'text-muted-foreground'"
                >
                    <Check v-if="step.done" class="size-5" />
                    <Clock3 v-else-if="step.active" class="size-5" />
                    <Circle v-else class="size-4" />
                </div>

                <div class="min-w-0 flex-1">
                    <div class="text-xs font-medium uppercase tracking-wide text-muted-foreground">
                        Step {{ index + 1 }}
                    </div>
                    <h2 class="mt-1 font-semibold">{{ step.title }}</h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ step.description }}
                    </p>

                    <div
                        v-if="index === 1 && ! profile.email_verified"
                        class="mt-4"
                    >
                        <Form
                            action="/email/verification-notification"
                            method="post"
                            v-slot="{ processing }"
                        >
                            <button
                                type="submit"
                                :disabled="processing"
                                class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground disabled:opacity-50"
                            >
                                <MailCheck class="size-4" />
                                {{ processing ? 'Sending…' : 'Resend verification email' }}
                            </button>
                        </Form>
                    </div>

                    <div
                        v-if="index === 2 && profile.email_verified && ! companySubmitted"
                        class="mt-4"
                    >
                        <Link
                            href="/verification/apply"
                            class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground"
                        >
                            <Building2 class="size-4" />
                            Add company and documents
                        </Link>
                    </div>

                    <div
                        v-if="index === 3 && companyRejected"
                        class="mt-4 space-y-3"
                    >
                        <div class="rounded-lg bg-destructive/10 p-3 text-sm text-destructive">
                            {{ company?.rejection_reason || 'The administrator requested corrections.' }}
                        </div>
                        <Link
                            href="/verification/apply"
                            class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground"
                        >
                            <FileCheck2 class="size-4" />
                            Correct and resubmit
                        </Link>
                    </div>

                    <div
                        v-if="index === 3 && companyPending"
                        class="mt-4"
                    >
                        <Link
                            href="/verification/status"
                            class="text-sm font-medium text-primary underline underline-offset-4"
                        >
                            View verification status
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <section
            v-if="! profile.is_admin && companyApproved"
            class="rounded-2xl border border-emerald-300 bg-emerald-50 p-6 text-emerald-950"
        >
            <div class="flex items-start gap-4">
                <BadgeCheck class="mt-1 size-7 shrink-0" />
                <div class="flex-1">
                    <h2 class="text-xl font-semibold">Your company is approved</h2>
                    <p class="mt-1 text-sm">You can now publish trade opportunities.</p>

                    <div class="mt-5 flex flex-wrap gap-3">
                        <Link
                            v-if="profile.requested_role === 'buyer' || profile.requested_role === 'both'"
                            href="/trade-posts/create?type=buy"
                            class="inline-flex items-center gap-2 rounded-lg bg-emerald-900 px-4 py-2 text-sm font-medium text-white"
                        >
                            <ShoppingCart class="size-4" />
                            Create buy request
                        </Link>

                        <Link
                            v-if="profile.requested_role === 'seller' || profile.requested_role === 'both'"
                            href="/trade-posts/create?type=sell"
                            class="inline-flex items-center gap-2 rounded-lg bg-emerald-900 px-4 py-2 text-sm font-medium text-white"
                        >
                            <Store class="size-4" />
                            Create sell offer
                        </Link>

                        <Link
                            v-if="profile.requested_role === 'broker'"
                            href="/trade-posts/create?type=broker"
                            class="inline-flex items-center gap-2 rounded-lg bg-emerald-900 px-4 py-2 text-sm font-medium text-white"
                        >
                            <Handshake class="size-4" />
                            Create broker opportunity
                        </Link>

                        <Link
                            href="/trade-posts"
                            class="rounded-lg border border-emerald-700 px-4 py-2 text-sm font-medium"
                        >
                            Browse marketplace
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
