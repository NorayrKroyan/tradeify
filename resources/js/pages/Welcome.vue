<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    BadgeCheck,
    Handshake,
    Search,
    ShieldCheck,
    ShoppingCart,
    Store,
} from '@lucide/vue';
</script>

<template>
    <Head title="Tradeify — Verified B2B marketplace" />

    <div class="min-h-screen bg-background text-foreground">
        <header class="sticky top-0 z-20 border-b bg-background/90 backdrop-blur">
            <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <Link href="/" class="block h-14 w-52 overflow-hidden" aria-label="Tradeify home">
                    <img src="/brand/tradeify-logo.png" alt="Tradeify" class="relative top-1/2 h-auto w-full -translate-y-1/2" />
                </Link>

                <nav class="flex items-center gap-2">
                    <Link href="/trade-posts" class="hidden rounded-xl px-4 py-2 text-sm font-semibold transition hover:bg-muted sm:inline-flex">
                        Marketplace
                    </Link>
                    <Link href="/login" class="rounded-xl border px-4 py-2 text-sm font-semibold transition hover:bg-muted">
                        Log in
                    </Link>
                    <Link href="/register" class="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground shadow-sm">
                        Join Tradeify
                    </Link>
                </nav>
            </div>
        </header>

        <main>
            <section class="relative overflow-hidden border-b">
                <div class="pointer-events-none absolute -left-24 top-8 size-80 rounded-full bg-blue-500/10 blur-3xl" />
                <div class="pointer-events-none absolute -right-24 bottom-0 size-80 rounded-full bg-emerald-500/10 blur-3xl" />

                <div class="relative mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 sm:py-24 lg:grid-cols-[1.2fr_0.8fr] lg:items-center lg:px-8">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full border bg-card px-3 py-1.5 text-sm font-semibold shadow-sm">
                            <ShieldCheck class="size-4 text-emerald-600" />
                            Verified companies. Clearer opportunities.
                        </div>

                        <h1 class="mt-6 max-w-4xl text-4xl font-black tracking-tight sm:text-5xl lg:text-6xl">
                            Global B2B trade built around
                            <span class="bg-gradient-to-r from-blue-600 to-emerald-500 bg-clip-text text-transparent">trust and clarity.</span>
                        </h1>
                        <p class="mt-6 max-w-2xl text-lg leading-8 text-muted-foreground">
                            Tradeify brings buyers, sellers and brokers into one verified marketplace. Publish what you need, show your target price, and connect around transparent commercial terms.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <Link href="/trade-posts" class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-3 font-semibold text-primary-foreground shadow-sm">
                                <Search class="size-5" /> Browse opportunities
                            </Link>
                            <Link href="/register" class="inline-flex items-center gap-2 rounded-xl border bg-card px-5 py-3 font-semibold shadow-sm transition hover:bg-muted">
                                Create verified account <ArrowRight class="size-5" />
                            </Link>
                        </div>

                        <div class="mt-8 flex flex-wrap gap-x-6 gap-y-3 text-sm text-muted-foreground">
                            <span class="inline-flex items-center gap-2"><BadgeCheck class="size-4 text-emerald-600" /> Company review</span>
                            <span class="inline-flex items-center gap-2"><BadgeCheck class="size-4 text-emerald-600" /> Moderated posts</span>
                            <span class="inline-flex items-center gap-2"><BadgeCheck class="size-4 text-emerald-600" /> Buyer, seller & broker roles</span>
                        </div>
                    </div>

                    <div class="rounded-3xl border bg-card p-5 shadow-xl shadow-blue-500/5 sm:p-6">
                        <div class="mb-5 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-muted-foreground">Marketplace preview</p>
                                <h2 class="mt-1 text-xl font-bold">One place for every side of the deal</h2>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="rounded-2xl border border-sky-200 bg-sky-50 p-4 dark:border-sky-900 dark:bg-sky-950/30">
                                <div class="flex items-start gap-3">
                                    <div class="rounded-xl bg-sky-100 p-2 text-sky-700 dark:bg-sky-900/50 dark:text-sky-300"><ShoppingCart class="size-5" /></div>
                                    <div><div class="font-bold">Buyer</div><p class="mt-1 text-sm text-muted-foreground">Publish demand, quantity, destination and target purchase price.</p></div>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900 dark:bg-emerald-950/30">
                                <div class="flex items-start gap-3">
                                    <div class="rounded-xl bg-emerald-100 p-2 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300"><Store class="size-5" /></div>
                                    <div><div class="font-bold">Seller</div><p class="mt-1 text-sm text-muted-foreground">Show available supply, asking price, origin and delivery terms.</p></div>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-violet-200 bg-violet-50 p-4 dark:border-violet-900 dark:bg-violet-950/30">
                                <div class="flex items-start gap-3">
                                    <div class="rounded-xl bg-violet-100 p-2 text-violet-700 dark:bg-violet-900/50 dark:text-violet-300"><Handshake class="size-5" /></div>
                                    <div><div class="font-bold">Broker</div><p class="mt-1 text-sm text-muted-foreground">Publish broker opportunities with direction, target transaction price and commission.</p></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">How it works</p>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight">A simple path from registration to trading</h2>
                </div>

                <div class="mt-10 grid gap-4 md:grid-cols-4">
                    <div v-for="(step, index) in [
                        ['Choose your role', 'Buyer, seller, both, or broker.'],
                        ['Upload authorization', 'Provide the license or mandate relevant to your activity.'],
                        ['Verify your company', 'Submit legal details and supporting documents.'],
                        ['Publish opportunities', 'Approved users can create moderated marketplace posts.'],
                    ]" :key="step[0]" class="rounded-2xl border bg-card p-5 shadow-sm">
                        <div class="flex size-9 items-center justify-center rounded-xl bg-primary text-sm font-bold text-primary-foreground">{{ index + 1 }}</div>
                        <h3 class="mt-4 font-bold">{{ step[0] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-muted-foreground">{{ step[1] }}</p>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t">
            <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-8 text-sm text-muted-foreground sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <span>Tradeify · Verified B2B trade marketplace</span>
                <div class="flex gap-4">
                    <Link href="/trade-posts" class="hover:text-foreground">Marketplace</Link>
                    <Link href="/login" class="hover:text-foreground">Log in</Link>
                    <Link href="/register" class="hover:text-foreground">Register</Link>
                </div>
            </div>
        </footer>
    </div>
</template>
