<template>
    <div>
      <!-- Tu contenido aquí -->
    </div>
  </template>
  
  <script setup>
  import { onMounted } from 'vue';
  
  onMounted(() => {
    const script = document.createElement('script');
    script.src = 'https://cdn.userway.org/widget.js';
    script.setAttribute('data-account', 'kjnkkEfZx0');
    document.body.appendChild(script);
  });
  </script>