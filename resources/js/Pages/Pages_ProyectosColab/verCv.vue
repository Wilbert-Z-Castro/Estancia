

<template>
    <Head title="Cv's enviados" />
    <AuthenticatedLayout>
        <template #header>
            <div class="justify-center" style="text-align: center;">
                Cv's recibidos
            </div>
        </template>
        {{ solicitudes }}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div  class="p-6 border-b border-gray-200">
                <div >
                    <div class="flex justify-center mt-6"   > 
                        <div class="flex flex-col items-center w-full bg-white border border-gray-200 rounded-lg shadow md:flex-row  dark:border-gray-700 dark:bg-gray-800 ">
                            <div v-for="a in solicitudes":key="a.idProyectoColab">
                                <div class="flex flex-col justify-between p-4 leading-normal w-full">
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
                                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                            Proyecto: {{ a.TituloProyecto}}  
                                        </h5>
                                        <h5 class="text-xl font-medium text-gray-900 dark:text-white sm:ml-4 sm:mt-0 mt-2">
                                        </h5>
                                    </div>
                                    
                                    <p class="mb-3 font-normal text-gray-300  whitespace-pre-wrap break-words">Cv's enviados por alumnos:</p>
                                    <p class="mb-3 font-normal text-gray-300  whitespace-pre-wrap break-words"></p>
                                    <div  class="flex flex-col sm:flex-row gap-4 p-4 justify-left  items-start sm:items-center mb-2" >
                                        <div v-for="i in a.solicitudes"   >
                                            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow ">
                                                <a href="#">
                                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 "></h5>
                                                </a>
                                                <p class="mb-3 font-normal text-gray-700 ">Nombre del documento:</p>
                                                <p class="mb-3 font-normal text-gray-700 ">{{NombreArchivo(i.pivot.Cv)  }} </p>
                                                <p class="mb-3 font-normal text-gray-700 ">Enviado por:{{ i.name }} {{ i.ApellidoP }} {{ i.ApellidoM }}</p>
                                                <a :href="route('ProyectosColab.show',{id:i.pivot.idPostulacionProyecto})" target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 ">
                                                    Ver documento
                                                    <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                                    </svg>
                                                </a>
                                                <div class="">
                                                    <br>
                                                    <hr/>
                                                    <br>
                                                    <button  type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Aceptar</button>
                                                    <button   type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Rechazar</button>
                                                </div>
                                            </div>
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
    solicitudes:{type: Object}
});

function NombreArchivo(archivo) {
    const partes = archivo.split('_');
    
    // Devuelve el nombre original (segundo elemento)
    return partes.length > 1 ? partes[1] : archivo;
}

const valoresIniciales = {
    
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

