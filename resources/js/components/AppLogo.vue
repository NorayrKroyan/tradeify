<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div class="flex size-9 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500/10 via-blue-500/10 to-emerald-500/10 ring-1 ring-sky-500/20">
        <AppLogoIcon class-name="size-7" />
    </div>
    <div class="ml-2 grid min-w-0 flex-1 text-left">
        <span class="truncate text-sm font-bold tracking-tight text-foreground">
            Trade<span class="text-blue-600">ify</span>
        </span>
        <span class="truncate text-[10px] font-medium uppercase tracking-[0.16em] text-muted-foreground">
            Verified B2B trade
        </span>
    </div>
</template>
