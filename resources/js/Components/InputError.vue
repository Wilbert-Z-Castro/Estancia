<script setup>
defineProps({
    message: {
        type: String,
    },
});

</script>

<template>
    <div v-show="message" v-on:focus="" >
        <p class="text-sm text-red-600">
            {{ message }}
        </p>
    </div>
</template>
