

<template>
    <Head title="Mensaje ponencia" />
    <AuthenticatedLayout>
        <template #header>
            <div class="justify-center" style="text-align: center;">
                Mensaje de ponencia
            </div>
        </template>
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div  class="p-6 border-b border-gray-200">
                <div v-if="aceptacionPonencia.Estado=='Aceptado' || aceptacionPonencia.Estado=='Terminado'">

                    <a :href="route('Ponencias.Reconocimiento',{id:aceptacionPonencia.idAceptacionPonencia})" target="_blank" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white  focus:ring-4 focus:outline-none focus:ring-cyan-200 ">
                        <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white  rounded-md group-hover:bg-opacity-0">
                            General Certificado
                        </span>
                    </a>
                </div>
                <div >
                    <div class="flex justify-center mt-6"   > 
                        <div class="flex flex-col items-center w-full bg-white border border-gray-200 rounded-lg shadow md:flex-row  dark:border-gray-700 dark:bg-gray-800 ">
                            
                            <div class="flex flex-col justify-between p-4 leading-normal w-full">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                        Ponencia: {{ aceptacionPonencia.ponencia.TituloPonencia }} 
                                    </h5>
                                    <h5 class="text-xl font-medium text-gray-900 dark:text-white sm:ml-4 sm:mt-0 mt-2">
                                    </h5>
                                </div>
                                
                                <p class="mb-3 font-normal text-gray-300  whitespace-pre-wrap break-words">Mensaje del invitado</p>
                                <p class="mb-3 font-normal text-gray-300  whitespace-pre-wrap break-words">{{ aceptacionPonencia.Mensaje }}</p>
                                <div  class="flex flex-col sm:flex-row gap-4 p-4 justify-left  items-start sm:items-center mb-2" >
                                    <div   v-for="a in aceptacionPonencia.imagenes":key="a.idimagenPonencia" >
                                        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow ">
                                            <a href="#">
                                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 "></h5>
                                            </a>
                                            <p class="mb-3 font-normal text-gray-700 ">Documento enviado: {{ NombreArchivo(a.URLArchivo) }}</p>
                                            
                                            <a :href="`/storage/${a.URLArchivo}`"  target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 ">
                                                Descargar documento
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
                <br>
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

import { Head, useForm} from '@inertiajs/vue3';
import { ref } from 'vue';



const props = defineProps({
    aceptacionPonencia:{type: Object}
});

function NombreArchivo(archivo) {
    return archivo.split('/').pop();
}

const valoresIniciales = {
    idAceptacionPonencia: props.aceptacionPonencia.idAceptacionPonencia,
};
const form = useForm(valoresIniciales);


const enviarFormulario = () => {
    form.post(route('Ponencias.Reconocimiento'), {
        onSuccess: () => {
            console.log('success');
        },
    });
}

</script>

