

<template>
    <Head title="Noticias" />

    <AuthenticatedLayout>
        <template #header>
            <div class="justify-center" style="text-align: center;">
                Noticias destacadas
            </div>
            
        </template>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                
                <a class="flex items-center mt-4 py-2 px-6 text-black-100" href="#" @click="showingTwoLevelMenu = !showingTwoLevelMenu">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"></path>
                    </svg>
                    <span class="mx-3">Categorías</span>
                    
                </a>
                <transition v-for="categoria in categorias" :key="categoria.idCatAnuncio"
                    enter-to-class="transition-all duration-300 ease-in-out"
                    enter-from-class="max-h-0 "
                    leave-from-class=" max-h-xl"
                    leave-to-class="max-h-0 ">
                    <div  v-show="showingTwoLevelMenu">
                        <ul
                            :class="getColorClass(categoria.Color)"

                            class="bg-opacity-50 overflow-hidden p-2 mx-4 mt-2 space-y-2 text-sm font-medium text-black  rounded-md shadow-inner"
                            aria-label="submenu"
                        >
                            <li class="px-2 py-1 transition-colors duration-150">
                                <Link class="block w-full h-full" :href="route('Panel.CategoriaNoticias',{id: categoria.idCatAnuncio})">{{ categoria.Nombre }}</Link>
                            </li>
                        </ul>
                        
                    </div>
                    
                </transition>
                <transition 
                    enter-to-class="transition-all duration-300 ease-in-out"
                    enter-from-class="max-h-0 "
                    leave-from-class=" max-h-xl"
                    leave-to-class="max-h-0 ">
                    <div  v-show="showingTwoLevelMenu">
                        <ul
                            class=" bg-gray-500 bg-opacity-50 overflow-hidden p-2 mx-4 mt-2 space-y-2 text-sm font-medium text-black  rounded-md shadow-inner"
                            aria-label="submenu"
                        >
                            <li class="px-2 py-1 transition-colors duration-150">
                                <Link class="block w-full h-full" :href="route('Panel.Noticias')">Todos</Link>
                            </li>
                        </ul>
                        
                    </div>
                    
                </transition>
                

                <div v-for="a in anuncios":key="a.idAnuncio"  class="flex justify-center mt-6"> 
                    <div class="flex flex-col items-center w-full bg-white border border-gray-200 rounded-lg shadow md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <PrimaryButton class="flex flex-col items-center w-full md:w-48">
                            <div v-if="a.imagenes!=null" v-for="imagen in a.imagenes" :key="imagen.idImagen">
                                    <img  :src="`/storage/${imagen.URL}`" alt="Imagen" class="object-cover  rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" />
                            </div>
                            <PrimaryButton @click="openModalViwe(a)" class="bg-green-600 hover:bg-green-700">
                                ver mas
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>

                            </PrimaryButton>
                        </PrimaryButton>
                        
                        <div class="flex flex-col justify-between p-4 leading-normal w-full ">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ a.Titulo }}
                                </h5>
                                <h5 class="text-xl font-medium text-gray-900 dark:text-white sm:ml-4 sm:mt-0 mt-2">
                                    {{ a.formatted_created_at }}
                                </h5>
                            </div>
                            <pre class="mb-3 font-normal text-gray-500 dark:text-gray-300 whitespace-pre-wrap break-words">{{ a.Contenido }}
                            </pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="showModalView" @close="closeModalViwe">
            <div class="p-6">
                <p > Título: <span  class="text-lg font-medium text-gray-900">{{ v.Titulo }}</span></p>
                <p > Categoría: <span  class="text-lg font-medium text-gray-900">{{ v.Categoria }}</span></p>
                <p > Contenido: <span  class="text-lg font-medium text-gray-900">{{ v.Contenido }}</span></p>
                <p > Imagen: 
                    <div v-for="imagen in  v.Imagen" :key="imagen.idImagen" class="flex justify-center items-center">
                        <img @error="imageError" @contextmenu.prevent :src="`/storage/${imagen.URL}`" alt="Imagen" class="w-full h-auto object-contain" />
                    </div>
                </p>
            </div>
            <div class="m-6 flex justify-end">
                <PrimaryButton @click="closeModalViwe">
                    Cerrar
                </PrimaryButton>
            </div>

        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import linkAgregar from '@/Components/linkAgregar.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref,onMounted } from 'vue';
import axios from 'axios';



const props = defineProps({
    anuncios:{
        type: Object,
        default: () => ({ data: [], links: [] })
    },
    categorias:{
        type: Object,
        default: () => ({ data: [], links: [] })
    }
});

const imageError = (event) => {
    event.target.src = '/img/NotFund.jpg';
};

const colorClasses = {
  red: 'bg-red-500',
  green: 'bg-green-500',
  blue: 'bg-blue-500',
  yellow: 'bg-yellow-500',
  orange: 'bg-orange-500',
  cyan: 'bg-cyan-500',
  pink: 'bg-pink-500',
  gray: 'bg-gray-500',
  violet: 'bg-violet-500',
  indigo: 'bg-indigo-500',
  purple: 'bg-purple-500',
};

function getColorClass(color) {
  return colorClasses[color] || 'bg-gray-100'; // Si no existe el color, devuelve 'bg-gray-100'
}

const colorbas = (color) => {
    if(color==null) return `bg-gray-500`;
  return `bg-${color}-500`; 
};
const v =ref({id:'',Titulo:'',Categoria:'',Contenido:'',Imagen:''});
const showModalView = ref(false);

const openModalViwe = (a) => {
    v.value.id = a.idAnuncio;
    v.value.Titulo = a.Titulo;
    v.value.Contenido = a.Contenido;
    v.value.Categoria = a.categoria.Nombre;
    v.value.Imagen = a.imagenes
    showModalView.value = true;

}

const closeModalViwe = () => {
   
    showModalView.value = false;

}
const load = ref(false);
const showingTwoLevelMenu = ref(false);
</script>

