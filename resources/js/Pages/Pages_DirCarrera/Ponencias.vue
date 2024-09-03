

<template>
    <Head title="About us" />
    <AuthenticatedLayout>
        <template #header>
            <div class="justify-center" style="text-align: center;">
                Panel de ponencias
            </div>
        </template>
        
        <br>
        <linkAgregar :href="route('Ponencias.create')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-2 size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Programar nueva ponencia
        </linkAgregar> 
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                {{   }}
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
                {{ ponencias }}
                <div v-for="a in ponencias":key="a.idPonencias">
                    <div  class="flex justify-center mt-6"  > 
                        <div class="flex flex-col items-center w-full bg-white border border-gray-200 rounded-lg shadow md:flex-row  dark:border-gray-700 dark:bg-gray-800 ">
                            
                            <div class="flex flex-col justify-between p-4 leading-normal w-full">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                        Ponencia: 
                                        {{ a.TituloPonencia }}
                                    </h5>
                                    <h5 class="text-xl font-medium text-gray-900 dark:text-white sm:ml-4 sm:mt-0 mt-2">
                                        {{a.Fecha}}
                                    </h5>
                                </div>
                                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 whitespace-pre-wrap break-words">Lista de invitaciones enviadas</p>
                                <div  class="flex flex-col sm:flex-row gap-4 p-4 justify-left  items-start sm:items-center mb-2" >
                                    <div  v-for="i in a.egresados":key="i.idEgresado"  >
                                        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow ">
                                            <a href="#">
                                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 ">Egresado: {{ i.user.name }}</h5>
                                            </a>
                                            <p class="mb-3 font-normal text-gray-700 ">Estado de la invitacion: {{i.pivot.Estado}}</p>
                                            
                                            <a v-show="i.pivot.Estado != 'Pendiente'" href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 ">
                                                Ver respuesta
                                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                           </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import linkAgregar from '@/Components/linkAgregar.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue';

import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    ponencias:{
        type: Object,
        default: () => ({ data: [], links: [] })
    },

});

const ponencias = ref(props.ponencias.data);
const links = ref(props.ponencias.links);

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return `${date.getDate().toString().padStart(2, '0')}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getFullYear()}`;
}

function NombreArchivo(archivo) {
    return archivo.split('/').pop();
}
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

