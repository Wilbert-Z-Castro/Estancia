<template>
    <Link :href="href" :class="classes">
        <slot name="icon" />
        <span class="mx-3">
            <slot />
        </span>
    </Link>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps(['href', 'active']);

const classes = computed(() => props.active
    ? 'flex items-center mt-4 py-2 px-6 bg-blanco bg-opacity-75 text-black-400'
    : 'flex items-center mt-4 py-2 px-6 text-gray-100'
);
</script>
