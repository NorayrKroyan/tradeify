<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <span
        :class="['relative block overflow-hidden rounded-md', className]"
        v-bind="$attrs"
        aria-hidden="true"
    >
        <img
            src="/brand/tradeify-logo.png"
            alt=""
            class="absolute left-0 top-1/2 h-auto w-[420%] max-w-none -translate-y-1/2"
        />
    </span>
</template>
