

<template>
    <Head title="Cv's Enviados" />

    <AuthenticatedLayout>
        <template #header>
            <div class="justify-center" style="text-align: center;">
                Cv's enviados
            </div>
        </template>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
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
                <div v-for="a in cvsEnviados":key="a.idOfertaTrabajo">
                    <div  class="flex justify-center mt-6" v-for="j in a.cv_ofertas" > 
                        <div class="flex flex-col items-center w-full bg-white border border-gray-200 rounded-lg shadow md:flex-row  dark:border-gray-700 dark:bg-gray-800 ">
                            
                            <div class="flex flex-col justify-between p-4 leading-normal w-full">
                             <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                        Publicación: {{ a.TituloOferta}}
                                    </h5>
                                    <h5 class="text-xl font-medium text-gray-900 dark:text-white sm:ml-4 sm:mt-0 mt-2">
                                        Cv enviado el: {{ formatDate(j.created_at) }}
                                    </h5>
                                </div>
                                <p  class="mb-3 font-normal text-gray-700 dark:text-gray-400 whitespace-pre-wrap break-words">
                                    <a :href="`/storage/${j.Cv}`" target="_blank" rel="noopener noreferrer">
                                        Descargar CV{{ NombreArchivo(j.Cv) }}
                                    </a></p>
                                
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
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    cvsEnviados:{
        type: Object,
    },

});

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

