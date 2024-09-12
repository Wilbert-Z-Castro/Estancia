<template>
                <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#" @click="showingTwoLevelMenu2 = !showingTwoLevelMenu2">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                
                            </svg>
                    <span class="mx-3"> Notificacaiones</span>
                    <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ notificaciones.length }}</span>
                    
                </a>
                <transition v-for="notificacion in notificaciones" :key="notificacion.idAceptacionPonencia"
                    enter-to-class="transition-all duration-300 ease-in-out"
                    enter-from-class="max-h-0 opacity-25"
                    leave-from-class="opacity-100 max-h-xl"
                    leave-to-class="max-h-0 opacity-0">
                    <div  v-show="showingTwoLevelMenu2">
                        <ul
                        class="overflow-hidden p-2 mx-4 mt-2 space-y-2 text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner"   
                        aria-label="submenu"
                        >
                        <li class="px-2 py-1 transition-colors duration-150">
                                <Link class="block w-full h-full" :href="route('Panel.Noticias')" >{{ notificacion.Estado }} {{ notificacion.ponencia.TituloPonencia }} </Link>
                            </li>
                        </ul>
                        
                    </div>
                    
                </transition>
      
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref,onMounted } from 'vue';
import axios from 'axios';

// Configurar Axios para enviar cookies de sesión
axios.defaults.withCredentials = true;

const notificaciones = ref([]);
const showingTwoLevelMenu2 = ref(false);
const fetchData = async () => {
  try {
    const response = await axios.get('/api/notificaciones', { withCredentials: true });
    notificaciones.value = response.data;
  } catch (error) {
    if (error.response && error.response.status === 401) {
      console.error('Usuario no autenticado');
    } else {
      console.error('Error al obtener notificaciones:', error);
    }
  }
};




onMounted(() => {
  fetchData();
});
</script>