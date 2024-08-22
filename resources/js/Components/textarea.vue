<script setup>
import { onMounted, ref } from 'vue';

// Define el modelo de datos que el componente recibirá
const model = defineModel({
    type: String,
    required: true,
});

// Referencia al elemento textarea
const textarea = ref(null);

// Enfoca el textarea si tiene el atributo autofocus
onMounted(() => {
    if (textarea.value.hasAttribute('autofocus')) {
        textarea.value.focus();
    }
});

// Expone el método focus para ser usado externamente
defineExpose({ focus: () => textarea.value.focus() });
</script>

<template>
    <!-- Elemento textarea con los mismos estilos que el input -->
    <textarea
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        v-model="model"
        ref="textarea"
    />
</template>
