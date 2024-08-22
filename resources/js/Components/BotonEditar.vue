<script setup>
import { defineProps } from 'vue';
const props = defineProps({
    href: {
        type: String,
        required: true
    }
});
</script>
<template>
    <a
        :href="href"
        class="inline-flex items-center my-3 px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
    
    >
        <slot />
</a>
</template>