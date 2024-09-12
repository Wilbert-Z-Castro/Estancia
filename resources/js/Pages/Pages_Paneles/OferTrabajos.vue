

<template>
    <Head title="About us" />

    <AuthenticatedLayout>
        <template #header>
            <div class="justify-center" style="text-align: center;">
                Noticias Relevantes
            </div>
        </template>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                {{ ofertas  }}
                <div v-if="$page.props.flash.message" class="inline-flex max-w-sm w-full bg-white shadow-md rounded-lg overflow-hidden ">
                    <div class="flex justify-center items-center w-12 bg-green-500">
                            <svg class="h-6 w-6 fill-current text-white" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z"/>
                            </svg>
                    </div>        
                    <div class="-mx-3 py-2 px-4">
                        <div class="mx-3">
                            <span class="text-green-500 font-semibold">Success</span>
                                <p class="text-gray-600 text-sm">{{ $page.props.flash.message }}</p>
                        </div>
                    </div>
                </div>
                <a class="flex items-center mt-4 py-2 px-6 text-black-100" href="#" @click="showingTwoLevelMenu = !showingTwoLevelMenu">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"></path>
                    </svg>
                    <span class="mx-3">Categorias</span>
                    
                </a>
                <transition 
                    enter-to-class="transition-all duration-300 ease-in-out"
                    enter-from-class="max-h-0 opacity-25"
                    leave-from-class="opacity-100 max-h-xl"
                    leave-to-class="max-h-0 opacity-0">
                    <div v-show="showingTwoLevelMenu">
                        <ul
                           
                            class="bg-blue-700  overflow-hidden p-2 mx-4 mt-2 space-y-2 text-sm font-medium text-black bg-opacity-50 rounded-md shadow-inner"
                            aria-label="submenu"
                        >
                            <li v-for="carrera in carreras" :key="carrera.idCarrera" class="hover:bg-blue-500 rounded-md  px-2 py-1 transition-colors duration-150">
                                <Link class="block w-full h-full " :href="route('Ofertas.CategoriaOfertasTrabajo',{id: carrera.idCarrera})">{{ carrera.NombreCarrera }}</Link>
                            </li>
                        </ul>
                        
                        
                    </div>
                </transition>
                <div v-for="a in ofertas":key="a.idOfertaTrabajo"  class="flex justify-center mt-6"> 
                    <div class="flex flex-col items-center w-full bg-white border border-gray-200 rounded-lg shadow md:flex-row  dark:border-gray-700 dark:bg-gray-800 ">
                        <PrimaryButton class="flex flex-col items-center w-full md:w-48">
                            <div v-if="a.Imagen!=null" >
                                    <img :src="`/storage/${a.Imagen}`" alt="Imagen" class="object-cover  rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" />
                                    <br>
                            </div>
                            <PrimaryButton @click="openModalViwe(a)" class="bg-green-600 hover:bg-green-700">
                                ver mas
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>

                            </PrimaryButton>
                        </PrimaryButton>
                        <div class="flex flex-col justify-between p-4 leading-normal w-full">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{a.TituloOferta}}
                                </h5>
                                <h5 class="text-xl font-medium text-gray-900 dark:text-white sm:ml-4 sm:mt-0 mt-2">
                                    {{ a.formatted_created_at }}
                                </h5>
                            </div>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 whitespace-pre-wrap break-words">Empresa:  {{ a.Empresa }}</p>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 whitespace-pre-wrap break-words">Sector de trabajo: {{ a.SectorEmpre }}</p>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 whitespace-pre-wrap break-words">Sector de trabajo: {{ a.SectorEmpre }}</p>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 whitespace-pre-wrap break-words">Carreras destino:
                                <p v-for="j,k in a.ofertas_carreras":key="j.idOfertaCarrera" >
                                        {{ j.carrera.NombreCarrera }}

                                </p>
                            </p>
                            <pre class="mb-3 font-normal text-gray-700 dark:text-gray-400 whitespace-pre-wrap break-words">Lista de Requisitos:
{{ a.Requisitos }}</pre>
                            <pre class="mb-3 font-normal text-gray-700 dark:text-gray-400 whitespace-pre-wrap break-words">Descripcion del trabajo:
{{ a.Descripcion }}</pre>
                            <div class="flex flex-row sm:flex-row justify-left items-start sm:items-center mb-2">
                                <linkAgregar :href="route('ofertasTrabajo.FormularioCV',{id:a.idOfertaTrabajo})">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-2 size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Enviar CV
                                </linkAgregar> 
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="showModalView" @close="closeModalViwe">
            <div class="p-6">
                <p > Titulo: <span  class="text-lg font-medium text-gray-900">{{ v.TituloOferta }}</span></p>
                <p > Empresa: <span  class="text-lg font-medium text-gray-900">{{ v.Empresa }}</span></p>
                <p > Descripción: <span  class="text-lg font-medium text-gray-900">{{ v.Descripcion }}</span></p>
                <p > Fecha de publicación: <span  class="text-lg font-medium text-gray-900">{{ v.FechaOferta }}</span></p>
                <p > Requisitos: <span  class="text-lg font-medium text-gray-900">{{ v.Requisitos }}</span></p>
                <p > Ubicación: <span  class="text-lg font-medium text-gray-900">{{ v.Ubicacion }}</span></p>
                <p > Sector Empresarial: <span  class="text-lg font-medium text-gray-900">{{ v.SectorEmpre }}</span></p>
                
                <p > Imagen: <span  class="text-lg font-medium text-gray-900">
                    <div  class="flex justify-center items-center">
                        <img v-if="v.Imagen" :src="`/storage/${v.Imagen}`" alt="Imagen" class="w-full h-auto object-contain" />
                    </div>
                </span></p>
                

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
import { ref } from 'vue';

const props = defineProps({
    ofertas:{
        type: Object,
    },
    carreras:{
        type: Object,
    },
});

const colorbas = (color) => {
    if(color==null) return `bg-gray-500`;
  return `bg-${color}-500`; 
};
const v =ref({id:'',TituloOferta:'',Empresa:'',Descripcion:'',FechaOferta:'',Imagen:'',ofertas_carreras:[],Requisitos:'',Ubicacion:'',SectorEmpre:''});

const showModalView = ref(false);

const openModalViwe = (a) => {

    v.value.id = a.idOfertaTrabajo;
    v.value.TituloOferta = a.TituloOferta;
    v.value.Empresa = a.Empresa;
    v.value.Descripcion = a.Descripcion;
    v.value.FechaOferta = a.FechaOferta;
    v.value.Imagen = a.Imagen;
    v.value.ofertas_carreras = a.ofertas_carreras;
    v.value.Requisitos = a.Requisitos;
    v.value.Ubicacion = a.Ubicacion;
    v.value.SectorEmpre = a.SectorEmpre;

    showModalView.value = true;

}

const closeModalViwe = () => {
   
    showModalView.value = false;

}

const showingTwoLevelMenu = ref(false);
</script>

