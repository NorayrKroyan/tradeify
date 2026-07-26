<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { BadgeCheck, Clock3, FileWarning, ShieldCheck } from '@lucide/vue';
import { computed } from 'vue';

const props = defineProps<{ company: any | null }>();

const statusLabel = computed(() => {
    const status = props.company?.verification_status;

    if (status === 'pending') return 'Submitted';
    if (status === 'under_review') return 'Under administrator review';
    if (status === 'approved') return 'Approved';
    if (status === 'rejected') return 'Corrections required';
    if (status === 'suspended') return 'Suspended';
    return 'Draft';
});
</script>

<template>
    <Head title="Company verification status" />

    <div class="mx-auto max-w-4xl space-y-6 p-6">
        <section class="rounded-2xl border bg-card p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between text-sm">
                <span class="font-medium">Step 4 of 4</span>
                <Link href="/dashboard" class="text-primary underline underline-offset-4">Setup dashboard</Link>
            </div>
            <div class="mb-6 h-2 overflow-hidden rounded-full bg-muted">
                <div class="h-full w-full rounded-full bg-primary" />
            </div>

            <div class="flex items-start gap-4">
                <ShieldCheck class="mt-1 size-8 shrink-0" />
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight">Company verification</h1>
                    <p class="mt-2 text-muted-foreground">Follow the administrator decision and your next action here.</p>
                </div>
            </div>
        </section>

        <section v-if="!company" class="rounded-2xl border bg-card p-6">
            <h2 class="text-xl font-semibold">No application submitted</h2>
            <p class="mt-2 text-muted-foreground">Add your company details and required documents to begin verification.</p>
            <Link href="/verification/apply" class="mt-5 inline-flex rounded-lg bg-primary px-4 py-2 font-medium text-primary-foreground">
                Start company verification
            </Link>
        </section>

        <section v-else class="rounded-2xl border bg-card p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <div class="text-sm text-muted-foreground">Company</div>
                    <div class="mt-1 text-xl font-semibold">{{ company.legal_name }}</div>
                    <div class="mt-1 text-sm text-muted-foreground">Registration: {{ company.registration_number }}</div>
                </div>
                <span class="w-fit rounded-full border px-4 py-2 text-sm font-medium">{{ statusLabel }}</span>
            </div>

            <div v-if="company.verification_status === 'pending' || company.verification_status === 'under_review'" class="mt-6 flex gap-4 rounded-xl border border-amber-300 bg-amber-50 p-5 text-amber-950">
                <Clock3 class="mt-1 size-6 shrink-0" />
                <div>
                    <h2 class="font-semibold">Administrator review in progress</h2>
                    <p class="mt-1 text-sm">No action is required now. You will receive an email when a decision is made.</p>
                </div>
            </div>

            <div v-if="company.verification_status === 'rejected'" class="mt-6 rounded-xl border border-destructive/40 bg-destructive/10 p-5">
                <div class="flex gap-3">
                    <FileWarning class="mt-1 size-6 shrink-0 text-destructive" />
                    <div>
                        <h2 class="font-semibold text-destructive">Corrections are required</h2>
                        <p class="mt-2 text-sm">{{ company.rejection_reason || 'Please review and correct your company application.' }}</p>
                        <Link href="/verification/apply" class="mt-4 inline-flex rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground">
                            Correct and resubmit
                        </Link>
                    </div>
                </div>
            </div>

            <div v-if="company.verification_status === 'approved'" class="mt-6 rounded-xl border border-emerald-300 bg-emerald-50 p-5 text-emerald-950">
                <div class="flex gap-3">
                    <BadgeCheck class="mt-1 size-6 shrink-0" />
                    <div>
                        <h2 class="font-semibold">Company approved</h2>
                        <p class="mt-1 text-sm">You can now create buy requests and sell offers according to your account type.</p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <Link href="/trade-posts/create" class="rounded-lg bg-emerald-900 px-4 py-2 text-sm font-medium text-white">Create trade post</Link>
                            <Link href="/my-posts" class="rounded-lg border border-emerald-700 px-4 py-2 text-sm font-medium">My posts</Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
